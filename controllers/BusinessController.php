<?php

namespace app\controllers;

use app\components\LanguageBehavior;
use app\components\MyUrl;
use app\models\Business;
use app\models\Resource;
use app\models\Rule;
use app\models\Service;
use app\models\User;
use app\models\UserBusiness;
use Exception;
use Throwable;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii;
use yii\helpers\Inflector;


/**
 * BusinessController implements the CRUD actions for Business model.
 */
class BusinessController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
                'languageBehavior' => [
                    'class' => LanguageBehavior::class,
                ],
            ]
        );
    }

    private function findModel($slug = null, $id = null)
    {
        if ($slug === null && $id === null) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        $condition = $id ? ['id' => $id] : ['slug' => $slug];
        $model = Business::find()->active()->andWhere($condition)->one();
        if (!$model) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        return $model;
    }

    public function actionCreate(): yii\web\Response|string
    {
        $model = new Business();
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                UserBusiness::addUserBusiness(Yii::$app->user->identity->user->id, $model->id, UserBusiness::ROLE_ADMIN);
                Yii::$app->session->setFlash('info', Yii::t('app', 'Business created'));
                return $this->redirect(MyUrl::to(['business/user/admin/'.$model->slug]));
            }
        } else {
            $model->loadDefaultValues();
        }
        return $this->render('create', ['model' => $model,]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionUpdate($slug): yii\web\Response|string
    {
        $model = $this->findModel(slug:$slug);
        if (!$model) {
            throw new NotFoundHttpException(Yii::t('app','Business not found.'));
        }
        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('info', Yii::t('app', 'Business updated'));
                return $this->redirect(MyUrl::to(['business/update/'.$model->slug]));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Error updating business.'));
            }
        }
        return $this->render('update', ['model' => $model,]);
    }

    private function softDeleteRelations($model)
    {
        foreach (['resources', 'services', 'rules'] as $relation) {
            foreach ($model->$relation() as $related) {
                if (!$related->softDelete()) {
                    return false;
                }
            }
        }
        $userBusinesses = UserBusiness::find()->where(['business_id' => $model->id]);
        foreach ($userBusinesses as $userBusiness) {
            if (!$userBusiness->delete()) {
                return false;
            }
        }
        return true;
    }

    /**
     * @throws Throwable
     * @throws NotFoundHttpException
     */
    public function actionDelete($id): yii\web\Response
    {
        $model = $this->findModel(id:$id);
        if (!$model) {
            throw new NotFoundHttpException(Yii::t('app','Business not found.'));
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->appointments->active()->count() > 0) {
                throw new Exception(Yii::t('app', 'Cannot delete business with active appointments.'));
            }
            if (!$this->softDeleteRelations($model)) {
                throw new Exception(Yii::t('app', 'Error deleting related entities.'));
            }
            if (!$model->softDelete()) {
                throw new Exception(Yii::t('app', 'Error deleting business.'));
            }
            $transaction->commit();
            Yii::$app->session->setFlash('info', Yii::t('app', 'Business deleted'));
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->goBack();
    }

    /**
     * @throws NotFoundHttpException
     * @throws BadRequestHttpException
     */
    private function handleBusinessUserChange($model, $role): bool
    {
        $userId = $this->request->post('id');
        if (!$userId) {
            throw new BadRequestHttpException(Yii::t('app', 'User ID is missing.'));
        }

        $user = User::find()->where(['id'=>$userId])->active()->one();
        if (!$user) {
            throw new NotFoundHttpException(Yii::t('app', 'User not found.'));
        }
        if (!Yii::$app->user->identity->user->superadmin && Yii::$app->user->identity->user->id === $user->id) {
            throw new BadRequestHttpException(Yii::t('app', 'You cannot change your own business role.'));
        }

        $postRole = $this->request->post('role') ?? '__addnew__';
        $userBusiness = UserBusiness::find()->where(['user_id' => $user->id, 'business_id' => $model->id])->one();

        if ($postRole === 'delete') {
            return $userBusiness ? $userBusiness->delete() : false;
        }

        if ($postRole === '__addnew__') {
            return $userBusiness ? $userBusiness->reassignUserBusiness($role) : UserBusiness::addUserBusiness($user->id, $model->id, $role);
        }

        if (!array_key_exists($postRole, Yii::$app->params['roles'])) {
            throw new BadRequestHttpException(Yii::t('app', 'Invalid role.'));
        }

        return $userBusiness ? $userBusiness->reassignUserBusiness($postRole) : UserBusiness::addUserBusiness($user->id, $model->id, $postRole);        
    }

    /**
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws BadRequestHttpException
     */
    public function actionUser($role, $slug)
    {
        $model = Business::find()->where(['slug' => $slug])->one();
        if (!$model) {
            throw new NotFoundHttpException(Yii::t('app', 'Business not found.'));
        }
        if (!array_key_exists($role, Yii::$app->params['roles'])) {
            throw new BadRequestHttpException(Yii::t('app', 'Invalid role.'));
        }
        if ($this->request->isPost) {
            try {
                $this->handleBusinessUserChange($model, $role);
                Yii::$app->session->setFlash('info', Yii::t('app', 'User role updated.'));
                return $this->redirect(MyUrl::to(['business/user/'.$role.'/'.$model->slug]));
            } catch (Exception $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('business_users', [
            'model' => $model,
            'dataProvider' => new ActiveDataProvider([
                'query' => $model->getUsersByRole($role)->active(),
                'pagination' => ['pageSize' => 10],
            ]),
            'role' => $role,
        ]);

    }

    private function handleDeleteRelation($slug, $id, $relatedModel, $relation)
    {
        if ($this->request->get('delete')) {
            if ($this->request->get('delete')!==$id) {
                throw new BadRequestHttpException(Yii::t('app', 'Posted values are missing.'));
            }
            if ($relatedModel->isNewRecord) {
                throw new NotFoundHttpException(Yii::t('app', 'Relation not found.'));
            }
            if ($relatedModel->softDelete()) {
                Yii::$app->session->setFlash('info', Yii::t('app', 'Relation deleted.'));
                return $this->redirect(MyUrl::to(["business/$relation/$slug"]));
            }
            Yii::$app->session->setFlash('error', Yii::t('app', 'Error deleting relation.').implode(' ', $relatedModel->getErrorSummary(true)));
        } else {
            $relatedModel->load(Yii::$app->request->post());
            if ($relatedModel->validate() && $relatedModel->save()) {
                Yii::$app->session->setFlash('info', Yii::t('app', 'Relation saved.'));
                return $this->redirect(MyUrl::to(["business/$relation/$slug"]));
            }            
            Yii::$app->session->setFlash('error', Yii::t('app', 'Error saving relation.').implode(' ', $relatedModel->getErrorSummary(true)));
        }
    }

    public function actionResource($slug, $id = null)
    {
        if (!($model = Business::find()->where(['slug' => $slug])->one())) {
            throw new NotFoundHttpException(Yii::t('app', 'Business not found.'));
        }

        $resource = Resource::find()->where(['id' => $id])->one() ?? new Resource(['business_id' => $model->id]);

        if ($id && $resource->isNewRecord) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'URL inconsistency detected. Are you trying to load a bookmarked page?'));
        }

        if ($this->request->isPost) {
            $this->handleDeleteRelation($slug, $id, $resource, 'resource');
        }

        return $this->render('business_relations', [
            'model' => $model,
            'dataProvider' => new ActiveDataProvider([
                'query' => $model->getResources()->andWhere(['deleted_at' => null]),
                'pagination' => ['pageSize' => 10],
            ]),
            'relation' => 'resource',
            'relationModel' => $resource,
            'relationTitle' => Yii::t('app', 'Resources'),
            'relationCreateTitle' => Yii::t('app', 'Create Resource'),
            'relationColumns' => ['name', 'resource_type'],
        ]);
    }

    public function actionRule($slug, $id = null)
    {
        if (!($model = Business::find()->where(['slug' => $slug])->one())) {
            throw new NotFoundHttpException(Yii::t('app', 'Business not found.'));
        }
        
        $rule = Rule::find()->where(['id' => $id])->one() ?? new Rule(['business_id' => $model->id]);

        if ($id && $rule->isNewRecord) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'URL inconsistency detected. Are you trying to load a bookmarked page?'));
        }

        if ($this->request->isPost) {
            $this->handleDeleteRelation($slug, $id, $rule, 'rule');
        }

        return $this->render('business_relations', [
            'model' => $model,
            'dataProvider' => new ActiveDataProvider([
                'query' => $model->getRules()->andWhere(['deleted_at' => null]),
                'pagination' => ['pageSize' => 10],
            ]),
            'relation' => 'rule',
            'relationModel' => $rule,
            'relationTitle' => Yii::t('app', 'Rules'),
            'relationCreateTitle' => Yii::t('app', 'Create Rule'),
            'relationColumns' => ['name', 'ruleset'],
        ]);
    }

    public function actionService($slug, $id = null)
    {
        if (!($model = Business::find()->where(['slug' => $slug])->one())) {
            throw new NotFoundHttpException(Yii::t('app', 'Business not found.'));
        }
        
        $service = Service::find()->where(['id' => $id])->one() ?? new Service(['business_id' => $model->id]);

        if ($id && $service->isNewRecord) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'URL inconsistency detected. Are you trying to load a bookmarked page?'));
        }

        if ($this->request->isPost) {
            $this->handleDeleteRelation($slug, $id, $service, 'service');
        }

        return $this->render('business_relations', [
            'model' => $model,
            'dataProvider' => new ActiveDataProvider([
                'query' => $model->getServices()->andWhere(['deleted_at' => null]),
                'pagination' => ['pageSize' => 10],
            ]),
            'relation' => 'service',
            'relationModel' => $service,
            'relationTitle' => Yii::t('app', 'Services'),
            'relationCreateTitle' => Yii::t('app', 'Create Service'),
            'relationColumns' => ['name', 'resource_type', 'expert_type', 'duration'],
        ]);
    }

}

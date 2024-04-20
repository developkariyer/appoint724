<?php

namespace app\controllers;

use app\components\ACL;
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
use yii\base\UserException;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii;


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
        if (!ACL::canBusinessCreate()) {
            throw new BadRequestHttpException(Yii::t('app', 'You are not allowed to create a business.'));
        }
        
        $model = new Business();
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                UserBusiness::addUserBusiness(Yii::$app->user->identity->user->id, $model->id, UserBusiness::ROLE_ADMIN);
                if (!Yii::$app->user->identity->user->superadmin) {
                    Yii::$app->user->identity->user->remainingBusinessCount -= 1;
                    Yii::$app->user->identity->user->save(false, ['remainingBusinessCount']);
                }
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

        if (!ACL::canBusinessUpdateDelete($model->id)) {
            throw new BadRequestHttpException(Yii::t('app', 'You are not allowed to update this business.'));
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
            foreach ($model->$relation as $related) {
                if (!$related->softDelete()) {
                    return false;
                }
            }
        }
        $userBusinesses = UserBusiness::find()->where(['business_id' => $model->id])->all();
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
    public function actionDelete($slug): yii\web\Response
    {
        $model = $this->findModel(slug:$slug);

        if ($model->id != $this->request->get('id')) {
            throw new BadRequestHttpException(Yii::t('app', 'Invalid request.'));
        }
        
        if (!ACL::canBusinessUpdateDelete($model->id)) {
            throw new ForbiddenHttpException(Yii::t('app', 'You are not allowed to delete this business.'));
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->getAppointments()->active()->count() > 0) {
                throw new UserException(Yii::t('app', 'Cannot delete business with active appointments.'));
            }
            if (!$this->softDeleteRelations($model)) {
                throw new UserException(Yii::t('app', 'Error deleting related entities.'));
            }
            if (!$model->delete()) {
                throw new UserException(Yii::t('app', 'Error deleting business.'));
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

        if (!ACL::isSuperAdmin() && ACL::isUserLoggedIn($user->id)) {
            throw new BadRequestHttpException(Yii::t('app', 'You cannot change your own business role.'));
        }

        $postRole = $this->request->post('role', '__addnew__');
        $userBusiness = UserBusiness::find()->where(['user_id' => $user->id, 'business_id' => $model->id])->one();

        if ($userBusiness && !ACL::canBusinessUserChange($model->id, $user->id)) {
            throw new ForbiddenHttpException(Yii::t('app', 'You are not allowed to modify this user in this business.'));
        }

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
        $model = $this->findModel(slug:$slug);

        if (!array_key_exists($role, Yii::$app->params['roles'])) {
            throw new BadRequestHttpException(Yii::t('app', 'Invalid role.'));
        }

        if (!ACL::canBusiness($model->id)) {
            throw new ForbiddenHttpException(Yii::t('app', 'You are not allowed to change user roles.'));
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

    private function getRelationData($model, $relation) {
        $getter = 'get' . ucfirst($relation) . 's';
        if (method_exists($model, $getter)) {
            return $model->$getter()->andWhere(['deleted_at' => null]);
        } else {
            throw new BadRequestHttpException("Invalid relation method: {$getter}");
        }
    }

    private function handleModifyRelation($slug, $id, $relatedModel, $relation)
    {
        if ($this->request->get('delete')) {
            if ($this->request->get('delete')!==$id) {
                throw new BadRequestHttpException(Yii::t('app', 'Invalid request.'));
            }
            if ($relatedModel->isNewRecord) {
                throw new NotFoundHttpException(Yii::t('app', 'Relation not found.'));
            }
            if ($relatedModel->delete()) {
                Yii::$app->session->setFlash('info', Yii::t('app', 'Relation deleted.'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Error deleting relation.').implode(' ', $relatedModel->getErrorSummary(true)));
            }
            return $this->redirect(MyUrl::to(["business/$relation/$slug"]));
        } else {
            if ($relatedModel->load(Yii::$app->request->post()) && $relatedModel->save()) {
                Yii::$app->session->setFlash('info', Yii::t('app', 'Relation saved.'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Error saving relation.').implode(' ', $relatedModel->getErrorSummary(true)));
            }
            return $this->redirect(MyUrl::to(["business/$relation/$slug"]));
        }
    }

    public function handleActionRelation($slug, $id = null, $modelRelation, $relation)
    {
        $model=$this->findModel(slug:$slug);

        if (!ACL::canBusiness($model->id)) {
            throw new ForbiddenHttpException(Yii::t('app', 'You are not allowed to view this page.'));
        }

        $relationModel = $modelRelation::find()->where(['id' => $id])->one() ?? new $modelRelation(['business_id' => $model->id]);

        if ($id && $relationModel->isNewRecord) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'URL inconsistency detected. Are you trying to load a bookmarked page?'));
        }

        if ($this->request->isPost) {
            $this->handleModifyRelation($slug, $id, $relationModel, $relation);
        }

        return $this->render('business_relations', [
            'model' => $model,
            'dataProvider' => new ActiveDataProvider([
                'query' => $this->getRelationData($model, $relation),
                'pagination' => ['pageSize' => 10],
            ]),
            'relation' => $relation,
            'relationModel' => $relationModel,
            'relationTitle' => Yii::t('app', ucfirst($relation).'s'),
            'relationCreateTitle' => Yii::t('app', 'Create New'),
            'relationColumns' => $relationModel->businessColumns(),
        ]);
    }

    public function actionResource($slug, $id = null)
    {
        return $this->handleActionRelation($slug, $id, Resource::class, 'resource');
    }

    public function actionRule($slug, $id = null)
    {
        return $this->handleActionRelation($slug, $id, Rule::class, 'rule');
    }

    public function actionService($slug, $id = null)
    {
        return $this->handleActionRelation($slug, $id, Service::class, 'service');
    }

}

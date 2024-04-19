<?php

namespace app\controllers;

use app\components\LanguageBehavior;
use app\components\MyUrl;
use app\models\Business;
use app\models\User;
use app\models\UserBusiness;
use Throwable;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
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

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionUpdate($slug): yii\web\Response|string
    {
        $model = $this->findModel($slug);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('info', Yii::t('app', 'Business updated'));
            //return $this->goBack();
        }

        return $this->render('update', ['model' => $model,]);
    }

    /**
     * @throws Throwable
     * @throws NotFoundHttpException
     */
    public function actionDelete($id): yii\web\Response
    {
        $this->findModel($id)->softDelete();

        return $this->goBack();
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function findModel($slug): ?Business
    {
        if (($model = Business::findOne(['slug' => $slug])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    private function saveUserBusiness($model, $userBusiness, $user, $role): bool
    {
        if ($userBusiness->save()) {
            Yii::$app->session->setFlash('info', Yii::t('app', '{user} role changed to {role} in {business}.', ['user' => $user->fullname, 'role' => $role, 'business' => $model->name]));
            return true;
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Error changing {user} role in {business}.', ['user' => $user->fullname, 'business' => $model->name]));
            return false;
        }
    }

    private function removeUserFromBusiness($model, $userBusiness, $user): bool
    {
        if (!$userBusiness) {
            Yii::$app->session->setFlash('warning', Yii::t('app', '{user} not in {business}.', ['user' => $user->fullname, 'business' => $model->name]));
            return false;
        }
    
        if ($userBusiness->softDelete()) {
            Yii::$app->session->setFlash('info', Yii::t('app', '{user} removed from {business}.', ['user' => $user->fullname, 'business' => $model->name]));
            return true;
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Error removing {user} from {business}.', ['user' => $user->fullname, 'business' => $model->name]));
            return false;
        }        
    }

    private function assignUserToBusiness($model, $userBusiness, $user, $role): bool
    {
        if ($userBusiness && $userBusiness->role === $role) {
            Yii::$app->session->setFlash('warning', Yii::t('app', '{user} already in {business} {role} role.', ['user' => $user->fullname, 'business' => $model->name, 'role' => $role]));
            return false;
        }
    
        if (!$userBusiness) {
            $userBusiness = new UserBusiness(['user_id' => $user->id, 'business_id' => $model->id, 'role' => $role]);
        } else {
            $userBusiness->role = $role;
        }

        return $this->saveUserBusiness($model, $userBusiness, $user, $role);
    }

    private function changeUserRole($model, $userBusiness, $user, $postRole): bool
    {
        if (!in_array($postRole, array_keys(Yii::$app->params['roles']))) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Invalid role.'));
            return false;
        }

        if (!$userBusiness) {
            $userBusiness = new UserBusiness(['user_id' => $user->id, 'business_id' => $model->id, 'role' => $postRole]);
        } else {
            if ($userBusiness->role === $postRole) {
                Yii::$app->session->setFlash('warning', Yii::t('app', '{user} already in {business} {role} role.', ['user' => $user->fullname, 'business' => $model->name, 'role' => $postRole]));
                return false;
            }
            $userBusiness->role = $postRole;
        }

        return $this->saveUserBusiness($model, $userBusiness, $user, $postRole);
    }

    /**
     * @throws NotFoundHttpException
     * @throws BadRequestHttpException
     */
    private function handleBusinessUserChange($model, $role): bool
    {
        $userId = $this->request->post('id');
        if (!$userId) {
            throw new BadRequestHttpException(Yii::t('app', 'Posted values are missing.'));
        }

        $user = User::findOne($userId);
        if (!$user) {
            throw new NotFoundHttpException(Yii::t('app', 'User not found.'));
        }

        $postRole = $this->request->post('role') ?? '__addnew__';
        $userBusiness = UserBusiness::findOne(['user_id' => $user->id, 'business_id' => $model->id]);

        if ($postRole === 'delete') {
            return $this->removeUserFromBusiness($model, $userBusiness, $user);
        }

        if ($postRole === '__addnew__') {
            return $this->assignUserToBusiness($model, $userBusiness, $user, $role);
        }

        return $this->changeUserRole($model, $userBusiness, $user, $postRole);
    }

    /**
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws BadRequestHttpException
     */
    public function actionUser($role, $slug): string
    {
        if (!($model = Business::find()->where(['slug' => $slug])->one())) {
            throw new NotFoundHttpException(Yii::t('app', 'Business not found.'));
        }
        if (!in_array($role, array_keys(Yii::$app->params['roles']))) {
            throw new BadRequestHttpException(Yii::t('app', 'Invalid role.'));
        }
        if ($this->request->isPost) {
            $this->handleBusinessUserChange($model, $role);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $model->getUsers($role),
            'pagination' => ['pageSize' => 10],
            'sort' => [
                'defaultOrder' => [
                    'first_name' => SORT_ASC, // Adjust according to your User model attributes and needs
                ]
            ],
        ]);
        return $this->render('business_users', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'role' => $role,
        ]);
    }

    public function actionResource($slug): string
    {
        if (!($model = Business::find()->where(['slug' => $slug])->one())) {
            throw new NotFoundHttpException(Yii::t('app', 'Business not found.'));
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $model->getResources(),
            'pagination' => ['pageSize' => 10],
            'sort' => [
                'defaultOrder' => [
                    'name' => SORT_ASC, // Adjust according to your Resource model attributes and needs
                ]
            ],
        ]);
        return $this->render('business_resources', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRule($slug): string
    {
        if (!($model = Business::find()->where(['slug' => $slug])->one())) {
            throw new NotFoundHttpException(Yii::t('app', 'Business not found.'));
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $model->getRules(),
            'pagination' => ['pageSize' => 10],
            'sort' => [
                'defaultOrder' => [
                    'name' => SORT_ASC, // Adjust according to your Resource model attributes and needs
                ]
            ],
        ]);
        return $this->render('business_rules', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionService($slug): string
    {
        if (!($model = Business::find()->where(['slug' => $slug])->one())) {
            throw new NotFoundHttpException(Yii::t('app', 'Business not found.'));
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $model->getServices(),
            'pagination' => ['pageSize' => 10],
            'sort' => [
                'defaultOrder' => [
                    'name' => SORT_ASC, // Adjust according to your Resource model attributes and needs
                ]
            ],
        ]);
        return $this->render('business_services', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

}

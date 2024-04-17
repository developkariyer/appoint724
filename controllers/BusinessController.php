<?php

namespace app\controllers;

use app\components\LanguageBehavior;
use app\models\Business;
use app\models\User;
use app\models\UserBusiness;
use Throwable;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
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
                return $this->goBack();
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
     * @throws StaleObjectException
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

    private function removeUserFromBusiness($model, $userBusiness, $user)
    {
        if (!$userBusiness) {
            Yii::$app->session->setFlash('warning', Yii::t('app', '{user} not in {business}.', ['user' => $user->fullname, 'business' => $model->name]));
            return;
        }
    
        if ($userBusiness->softDelete()) {
            Yii::$app->session->setFlash('info', Yii::t('app', '{user} removed from {business}.', ['user' => $user->fullname, 'business' => $model->name]));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Error removing {user} from {business}.', ['user' => $user->fullname, 'business' => $model->name]));
        }        
    }

    private function assignUserToBusiness($model, $userBusiness, $user, $role)
    {
        if ($userBusiness && $userBusiness->role === $role) {
            Yii::$app->session->setFlash('warning', Yii::t('app', '{user} already in {business} {role} role.', ['user' => $user->fullname, 'business' => $model->name, 'role' => $role]));
            return;
        }
    
        if (!$userBusiness) {
            $userBusiness = new UserBusiness(['user_id' => $user->id, 'business_id' => $model->id, 'role' => $role]);
        } else {
            $userBusiness->role = $role;
        }
    
        if ($userBusiness->save()) {
            Yii::$app->session->setFlash('info', Yii::t('app', '{user} role changed to {role} in {business}.', ['user' => $user->fullname, 'role' => $role, 'business' => $model->name]));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Error changing {user} role in {business}.', ['user' => $user->fullname, 'business' => $model->name]));
        }
    }

    private function changeUserRole($model, $userBusiness, $user, $postRole)
    {
        if (!in_array($postRole, array_keys(Yii::$app->params['roles']))) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Invalid role.'));
            return;
        }

        if (!$userBusiness) {
            $userBusiness = new UserBusiness(['user_id' => $user->id, 'business_id' => $model->id, 'role' => $postRole]);
        } else {
            if ($userBusiness->role === $postRole) {
                Yii::$app->session->setFlash('warning', Yii::t('app', '{user} already in {business} {role} role.', ['user' => $user->fullname, 'business' => $model->name, 'role' => $postRole]));
                return;
            }
            $userBusiness->role = $postRole;
        }
    
        if ($userBusiness->save()) {
            Yii::$app->session->setFlash('info', Yii::t('app', '{user} role changed to {role} in {business}.', ['user' => $user->fullname, 'role' => $postRole, 'business' => $model->name]));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Error changing {user} role in {business}.', ['user' => $user->fullname, 'business' => $model->name]));
        }
    }

    private function handleBusinessUserChange($model, $role)
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

    public function actionUser($role, $slug)
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
            'pagination' => ['pageSize' => 20],
            'sort' => [
                'defaultOrder' => [
                    'first_name' => SORT_ASC, // Adjust according to your User model attributes and needs
                ]
            ],
        ]);
        return $this->render('business_users.php', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'role' => $role,
        ]);
    }

}

<?php

namespace app\controllers;

use app\components\ACL;
use app\components\LanguageBehavior;
use app\models\Business;
use app\models\UserBusiness;
use Exception;
use Yii;
use app\models\User;
use app\models\Authidentity;
use app\models\form\UserForm;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\bootstrap5\ActiveForm;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\components\MyUrl;

class UserController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['register'],
                        'allow' => true,
                        'roles' => ['?'], // Guest users
                    ],
                    [
                        'actions' => ['update', 'password', 'search', 'add'],
                        'allow' => true,
                        'roles' => ['@'], // Authenticated users
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    if (Yii::$app->user->isGuest) {
                        return $action->controller->redirect(MyUrl::to(['user/register']));
                    } else {
                        return $action->controller->redirect(MyUrl::to(['user/update']));
                    }
                },
            ],
            'languageBehavior' => [
                'class' => LanguageBehavior::class,
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function actionAdd(): Response|array|string
    {
        if (ACL::isGuest()) {
            return $this->goHome();
        }

        if (!Yii::$app->request->get('slug') || !($business = Business::find()->where(['slug' => Yii::$app->request->get('slug')])->active()->one())) {
            throw new Exception(Yii::t('app', 'Invalid business.'));
        }

        if (!Yii::$app->request->get('role') || !in_array(Yii::$app->request->get('role'), array_keys(Yii::$app->params['roles']))) {
            throw new Exception(Yii::t('app', 'Invalid role.'));
        }

        if (!ACL::canUserAdd($business->id)) {
            throw new Exception(Yii::t('app', 'You are not authorized to perform this action.'));
        }

        $model = new UserForm();
        $model->scenario = UserForm::SCENARIO_ADD;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = new User();
            $user->attributes = $model->attributes;
            if ($user->save()) {
                if (UserBusiness::addUserBusiness($user->id, $business->id, Yii::$app->request->get('role'))) {
                    Yii::$app->session->setFlash('info', Yii::t('app', '{user} created and added to {business} {role} role.', ['user' => $user->fullname, 'business' => $business->name, 'role' => Yii::$app->request->get('role')]));
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Error adding {user} to {business}.', ['user' => $user->fullname, 'business' => $business->name]));
                }
            } else {
                Yii::debug($user->getAttributes());
                Yii::debug($user->getErrors());
                Yii::$app->session->setFlash('error', Yii::t('app', 'Error creating {user}.', ['user' => $user->fullname]));
            }
            return $this->redirect(["business/user/".Yii::$app->request->get('role')."/".$business->slug]);
        }

        return $this->render('user', [
            'model' => $model,
            'slug' => Yii::$app->request->get('slug'),
            'role' => Yii::$app->request->get('role'),
        ]);
    }

    public function actionRegister(): Response|array|string
    {
        if (!ACL::isGuest()) {
            return $this->goHome();
        }
        
        $model = new UserForm();
        $model->scenario = UserForm::SCENARIO_REGISTER;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();

            $user = new User();
            $user->attributes = $model->attributes;
            $user->remainingBusinessCount = 1;
            try {
                if ($user->save()) {
                    $authidentity = new Authidentity();
                    $authidentity->user_id = $user->id;
                    $authidentity->secret = Yii::$app->security->generatePasswordHash($model->password);
                    $authidentity->type = Authidentity::AUTHTYPE_PASSWORD;
                    if ($authidentity->save()) {
                        $transaction->commit();
                        Yii::$app->user->login($authidentity);
                        Yii::$app->session->setFlash('success', Yii::t('app', 'User has been registered successfully.'));
                        return $this->goHome();
                    } else {
                        $transaction->rollBack();
                        $model->addError('password', Yii::t('app', 'Unable to create Auth credentials.'));
                    }
                } else {
                    $transaction->rollBack();
                    $model->addError('email', Yii::t('app', 'Unable to create User.'));                
                }
            } catch (Exception) {
                $transaction->rollBack();
                $model->addError('email', Yii::t('app', 'An error occurred.'));
            }
        }
        return $this->render('user', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id = null): Response|array|string
    {
        if (ACL::isGuest()) {
            return $this->goHome();
        }

        $id = $id ?? Yii::$app->user->identity->user->id;

        if (!ACL::canUserUpdate($id)) {
            throw new ForbiddenHttpException(Yii::t('app', 'You are not authorized to perform this action.'));
        }
        $restricted = (ACL::isSuperAdmin() || ACL::isUserLoggedIn($id)) ? false : true;

        $model = new UserForm();
        $model->scenario = UserForm::SCENARIO_UPDATE;

        if (!Yii::$app->session->has('oldUrl')) {
            Yii::$app->session->set('oldUrl', Yii::$app->request->referrer);
        }

        if (!($user = User::find()->where(['id'=>$id])->active()->one())) {
            throw new NotFoundHttpException(Yii::t('app', 'User not found.'));
        }

        $model->attributes = $user->attributes;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user->attributes = $model->attributes;
            if ($user->isAttributeChanged('tcno')) {
                $user->tcnoverified = 0;
            }
            if ($user->isAttributeChanged('gsm')) {
                $user->gsmverified = 0;
            }
            if ($user->isAttributeChanged('email')) {
                $user->emailverified = 0;
            }
            if ($user->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', '{user} has been updated successfully.', ['user' => $user->fullname]));
                $oldUrl = Yii::$app->session->get('oldUrl');
                Yii::$app->session->remove('oldUrl');
                return $this->redirect($oldUrl);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Unable to update {user}.', ['user' => $user->fullname]));
                Yii::error($user->getErrors(), __METHOD__);
            }
        }

        return $this->render('user', [
            'model' => $model,
            'restricted' => $restricted,
        ]);
    }

    /**
     * @throws \yii\base\Exception
     */
    public function actionPassword(): Response|array|string
    {
        if (ACL::isGuest()) {
            return $this->goHome();
        }

        $model = new UserForm();
        $model->scenario = UserForm::SCENARIO_PASSWORD;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $authidentity = Authidentity::findOne(['user_id' => Yii::$app->user->identity->user->id, 'type' => Authidentity::AUTHTYPE_PASSWORD]);
            if (!$authidentity) {
                $authidentity = new Authidentity();
                $authidentity->user_id = Yii::$app->user->identity->user->id;
                $authidentity->type = Authidentity::AUTHTYPE_PASSWORD;
            }
            $authidentity->secret = Yii::$app->security->generatePasswordHash($model->password);
            if ($authidentity->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Password has been updated successfully.'));
                return $this->goHome();
            } else {
                $model->addError('password', Yii::t('app', 'Unable to update password.'));
            }
        }

        return $this->render('user', [
            'model' => $model,
        ]);
    }

    /**
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function actionSearch()
    {
        if (ACL::isGuest()) {
            throw new ForbiddenHttpException(Yii::t('app', 'You are not authorized to perform this action.'));
        }
        $search = trim(Yii::$app->request->get('search'));
        $role = Yii::$app->request->get('role');
        $business_id = Yii::$app->request->get('business_id');

        if (!in_array($role, array_keys(Yii::$app->params['roles']))) {
            throw new BadRequestHttpException('Invalid role.');
        }

        if (!($business = Business::find()->where(['id'=>$business_id])->active()->one())) {
            throw new NotFoundHttpException('Invalid business.'); // NotFoundHttpException is more specific
        }

        if (strlen($search) < 1) {
            $query = $business->getUsers()->andWhere(['id' => 0]);
        } else {
            $query = $business->getAvailableUsers()->andWhere([
                'or',
                ['like', 'fullname', $search],
                ['like', 'email', $search],
                ['like', 'gsm', $search]
            ]);
        }

        return $this->renderPartial('_user_search', [
            'dataProvider' => new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]),
            'slug' => $business->slug,
            'role' => $role,
        ]);
    }

}    
<?php

namespace app\controllers;

use app\components\LanguageBehavior;
use app\models\Business;
use app\models\UserBusiness;
use Exception;
use Yii;
use app\models\User;
use app\models\Authidentity;
use app\models\form\UserForm;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\bootstrap5\ActiveForm;
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
                        'actions' => ['update', 'password', 'search'],
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

    public function actionRegister(): Response|array|string
    {
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

    public function actionUpdate(): Response|array|string
    {
        $model = new UserForm();
        $model->scenario = UserForm::SCENARIO_UPDATE;
        $user = User::findOne(Yii::$app->user->identity->user->id);

        $model->attributes = $user->attributes;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user->attributes = $model->attributes;
            // if tcno, gsm or email attributes changed in update form, set tcnoverified, gsmverified an emailverified to false respectively
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
                return $this->goHome();
            } else {
                Yii::error($user->getErrors(), __METHOD__);
            }
        }

        return $this->render('user', [
            'model' => $model,
        ]);
    }

    /**
     * @throws \yii\base\Exception
     */
    public function actionPassword(): Response|array|string
    {
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
     * Summary of actionSearch
     * @return array|string
     */
    public function actionSearch()
    {
        //Yii::$app->response->format = Response::FORMAT_JSON;
        $search = trim(Yii::$app->request->get('search'));
        $role = Yii::$app->request->get('role');
        $business_id = Yii::$app->request->get('business_id');

        if (!in_array($role, array_keys(Yii::$app->params['roles']))) {
            throw new Exception('Invalid user type in actionSearch.');
        }

        $business = Business::findOne($business_id);

        if (!$business) {
            throw new Exception('Invalid business in actionSearch.');
        }

        if (strlen($search) < 3) {
            $query = $business->getUsers()->andWhere(['id' => 0]);
        } else {
            $query = $business->getAvailableUsers($role)->andWhere([
                'or',
                ['like', 'first_name', $search],
                ['like', 'last_name', $search],
                ['like', 'email', $search],
                ['like', 'gsm', $search]
            ]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        Yii::debug($role);
        Yii::debug($business->slug);

        return $this->renderPartial('_user_search', [
            'dataProvider' => $dataProvider,
            'slug' => $business->slug,
            'role' => $role,
        ]);
    }

}    
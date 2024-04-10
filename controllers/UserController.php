<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Authidentity;
use app\models\UserForm;
use yii\web\Controller;
use yii\bootstrap5\ActiveForm;
use yii\web\Response;
use app\components\MyUrl;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['register'],
                        'allow' => true,
                        'roles' => ['?'], // Guest users
                    ],
                    [
                        'actions' => ['update', 'password'],
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
                'class' => \app\components\LanguageBehavior::class,
            ],
        ];
    }

    public function actionRegister()
    {
        $model = new UserForm();
        $model->scenario = UserForm::SCENARIO_REGISTER;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $retval = ActiveForm::validate($model);
            return $retval;
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
            } catch (\Exception $e) {
                $transaction->rollBack();
                $model->addError('email', Yii::t('app', 'An error occurred.'));
            }
        }
        return $this->render('user', [
            'model' => $model,
        ]);
    }

    public function actionUpdate()
    {
        $model = new UserForm();
        $model->scenario = UserForm::SCENARIO_UPDATE;
        $user = User::findOne(Yii::$app->user->identity->user->id);

        $model->attributes = $user->attributes;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $retval = ActiveForm::validate($model);
            return $retval;
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user->attributes = $model->attributes;
            // if tcno, gsm or email attributes changed in update form, set tcnoverified, gsmverified an emailverified to false respectively
            if ($user->isAttributeChanged('tcno')) {
                $user->tcnoverified = false;
            }
            if ($user->isAttributeChanged('gsm')) {
                $user->gsmverified = false;
            }
            if ($user->isAttributeChanged('email')) {
                $user->emailverified = false;
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

    public function actionPassword()
    {
        $model = new UserForm();
        $model->scenario = UserForm::SCENARIO_PASSWORD;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $retval = ActiveForm::validate($model);
            return $retval;
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
}
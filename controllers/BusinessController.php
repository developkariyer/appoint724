<?php

namespace app\controllers;

use app\components\LanguageBehavior;
use app\models\Business;
use app\models\User;
use app\models\UserBusiness;
use Exception;
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
        $this->findModel($id)->delete();

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

    private function handleBusinessUserChange($model, $role)
    {
        if (!$this->request->post('id') || !$this->request->post('action')) {
            throw new BadRequestHttpException(Yii::t('app', 'Posted values are missing.'));
        }

        if (!($user = User::findOne($this->request->post('id')))) {
            throw new NotFoundHttpException(Yii::t('app', 'User not found.'));
        }

        $addUserBusiness = $this->request->post('action') == 2 ? true : false;

        if ($addUserBusiness) {
            if (UserBusiness::exists($model->id, $user->id)) {
                Yii::$app->session->setFlash('warning', Yii::t('app', '{user} already in {business}.', ['user' => $user->fullname,'business' => $model->name,]));
            } else {
                if (UserBusiness::addUserBusiness($this->request->post('id'), $model->id, $role)) {
                    Yii::$app->session->setFlash('info', Yii::t('app', '{user} added to {business} {role} role.', ['user' => $user->fullname,'business' => $model->name,'role' => $role,]));
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Error adding {user} to {business}.', ['user' => $user->fullname,'business' => $model->name,]));
                }
            }
        } else {
            if (UserBusiness::exists($model->id, $user->id)) {
                if (UserBusiness::deleteUserBusiness($this->request->post('id'), $model->id)) {
                    Yii::$app->session->setFlash('info', Yii::t('app', '{user} removed from {business}.', ['user' => $user->fullname,'business' => $model->name,]));
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Error removing {user} from {business}.', ['user' => $user->fullname,'business' => $model->name,]));
                }
            } else {
                Yii::$app->session->setFlash('warning', Yii::t('app', '{user} not in {business}.', ['user' => $user->fullname,'business' => $model->name,]));
            }
        }
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

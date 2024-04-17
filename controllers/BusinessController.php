<?php

namespace app\controllers;

use app\components\LanguageBehavior;
use app\models\Business;
use Exception;
use Throwable;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
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

    public function actionUser($userType, $slug)
    {
        $model = Business::find()->where(['slug' => $slug])->one();
        if (!$model) {
            throw new Exception('Business not found.');
        }

        if (!in_array($userType, array_keys(Yii::$app->params['userTypes']))) {
            throw new Exception('Invalid user type.');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $model->getUsers($userType),
            'pagination' => [
                'pageSize' => 20, // Adjust as needed
            ],
            'sort' => [
                'defaultOrder' => [
                    'first_name' => SORT_ASC, // Adjust according to your User model attributes and needs
                ]
            ],
        ]);

        return $this->render('_business_users.php', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'userType' => $userType,
        ]);
    }

}

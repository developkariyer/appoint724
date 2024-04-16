<?php

namespace app\controllers;

use app\components\LanguageBehavior;
use app\models\Business;
use app\models\query\BusinessSearch;
use Throwable;
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

    /**
     * Lists all Business models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new BusinessSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * @throws NotFoundHttpException
     */
    public function actionView(string $slug): string
    {
        return $this->render('view', [
            'model' => $this->findModel($slug),
        ]);
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
            return $this->goBack();
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
}

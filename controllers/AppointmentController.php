<?php

namespace app\controllers;

use app\components\LanguageBehavior;
use app\models\Appointment;
use app\models\Business;
use DateTime;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;


/**
 * AppointmentController implements the CRUD actions for Appointment model.
 */
class AppointmentController extends Controller
{
    
    public function getEvents()
    {
        return [
            [
                'title' => 'Morning Meeting #1',
                'start' => DateTime::createFromFormat('H:i', '03:00'),
                'duration' => 50,
                'day' => 0,
            ],
            [
                'title' => 'Morning Meeting #2',
                'start' => DateTime::createFromFormat('H:i', '05:00'),
                'duration' => 50,
                'day' => 1,
            ],
            [
                'title' => 'Morning Meeting #3',
                'start' => DateTime::createFromFormat('H:i', '07:00'),
                'duration' => 150,
                'day' => 2,
            ],
            [
                'title' => 'Morning Meeting #4',
                'start' => DateTime::createFromFormat('H:i', '09:00'),
                'duration' => 150,
                'day' => 0,
            ],
            [
                'title' => 'Morning Meeting #5',
                'start' => DateTime::createFromFormat('H:i', '10:00'),
                'duration' => 150,
                'day' => 0,
            ],
            [
                'title' => 'Morning Meeting #6',
                'start' => DateTime::createFromFormat('H:i', '13:00'),
                'duration' => 50,
                'day' => 0,
            ],
            [
                'title' => 'Lunch Break',
                'start' => DateTime::createFromFormat('H:i', '15:30'),
                'duration' => 90,
                'day' => 0,
            ],
        ];
    }

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

    private function findModel($id)
    {
        $model = Appointment::find()->active()->andWhere(['id' => $id])->one();
        if (!$model) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        return $model;
    }

    public function actionEvents($slug)
    {
        return $this->asJson($this->events);
    }

    public function actionView($slug)
    {
        Yii::$app->session->setFlash('info', Yii::t('app', 'Try moving "Morning Meeting" events around :)'));
    
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        $pixPerHour = 40;

        return $this->render('view1', ['events' => $this->events, 'days' => array_values($days), 'pixPerHour' => $pixPerHour]);
    }

}
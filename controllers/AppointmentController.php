<?php

namespace app\controllers;

use app\components\LanguageBehavior;
use app\models\Appointment;
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
        $events = [];
        for ($t=0;$t<10;$t++) {
            $events[] = [
                'title' => "Morning Meeting $t",
                //'start' => DateTime::createFromFormat('H:i', '08:00')->modify("+".rand(0, 480)." minutes"),
                'start' => DateTime::createFromFormat('H:i', '00:00')->modify("+".($t*120)." minutes"),
                'duration' => 100,
                'day' => 0,
            ];
        }
        for ($t=0;$t<5;$t++) {
            $events[] = [
                'title' => "Morning Meeting $t x",
                'start' => DateTime::createFromFormat('H:i', '06:15')->modify("+".($t*120)." minutes"),
                'duration' => 180,
                'day' => 0,
            ];
        }
/*
        for ($t=0;$t<50;$t++) {
            $events[] = [
                'title' => "Morning Meeting $t",
                'start' => DateTime::createFromFormat('H:i', '08:00')->modify("+".rand(0, 480)." minutes"),
                'duration' => 60,
                'day' => 0,
            ];
        }
*/  
        return $events;
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
        $days = ['Monday', 'Tuesday', 'Wednesday']; //, 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        $pixPerHour = 40;

        return $this->render('view', ['events' => $this->events, 'days' => array_values($days), 'pixPerHour' => $pixPerHour]);
    }

}
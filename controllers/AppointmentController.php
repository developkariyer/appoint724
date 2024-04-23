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
 
    public function getEvents()
    {
        $events = [];
        for ($t=0;$t<50;$t++) {
            $startMinute = rand(1,1000);
            $endMinute = $startMinute + rand(30,90);
            $events[] = [
                'id' => 'A'.md5($t*7),
                'title' => "Morning Meeting $t",
                'start' => '2021-01-01T08:00:00',
                'end' => '2021-01-01T09:00:00',
                //'startMinute' => $startMinute,
                //'endMinute' => $endMinute,
                //'day' => 0,
            ];
        }
        return $events;
    }

    public function actionEvents($slug)
    {
        return $this->asJson($this->events);
    }

    public function actionView($slug)
    {
        $days = ['Monday']; //, 'Tuesday', 'Wednesday']; //, 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        $pixPerHour = 80;

        return $this->render('view', ['events' => $this->events, 'days' => array_values($days), 'pixPerHour' => $pixPerHour]);
    }

}
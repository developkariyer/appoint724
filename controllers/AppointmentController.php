<?php

namespace app\controllers;

use app\components\LanguageBehavior;
use app\models\Appointment;
use DateTime;
use DateTimeZone;
use IntlDateFormatter;
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
 
    public function getEvents($userTimeZone = null)
    { // TODO: timezone yerine başlangıç bitiş zamanları gelmeli. Timezone serverdan alınmalı
        $cache = Yii::$app->request->get('cache', true);
        if ($cache && Yii::$app->session->has('events')) {
            $events = Yii::$app->session->get('events');
            return $events;
        }

        $utcTimeZone = new DateTimeZone('UTC');
        $userTimeZone = new DateTimeZone('Europe/Istanbul');

        $events = [];
        for ($t = 0; $t < 50; $t++) {
            $randomDateTime = new DateTime('now', $utcTimeZone);
            $randomDateTime->modify('-1 day');
            $randomDateTime->modify('+' . mt_rand(0, 7*24) . ' hour');

            $startDateTime = clone $randomDateTime;
            $endDateTime = clone $randomDateTime;

            $endDateTime->modify('+' . mt_rand(30, 120) . ' minute');

            $startDateTime->setTimezone($userTimeZone);
            $endDateTime->setTimezone($userTimeZone);

            $events[] = [
                'id' => 'A' . md5($t * 7),
                'title' => "Morning Meeting $t",
                'start' => $startDateTime->format('Y-m-d\TH:i:00'),
                'end' => $endDateTime->format('Y-m-d\TH:i:00'),
            ];
        }

        Yii::$app->session->set('events', $events);
        return $events;
    }

    public function actionEvents($slug)
    {
        return $this->asJson($this->getEvents());
    }

    public function actionView($slug)
    {
        $days = [];
        $today = new DateTime('now', new DateTimeZone('Europe/Istanbul'));
        $today->setTime(0,0);
        for ($i = 0; $i < 7; $i++) {

            $days[] = clone $today;
            $today->modify('+1 day');
        }

        $pixPerHour = 57;

        return $this->render('dayview', ['events' => $this->events, 'showDays' => array_values($days), 'pixPerHour' => $pixPerHour]);
    }

}
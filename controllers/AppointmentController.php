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
//            return $events;
        }

        $utcTimeZone = new DateTimeZone('UTC');
        $userTimeZone = new DateTimeZone('Europe/Istanbul');

        $events = [];
        for ($t = 0; $t < 500; $t++) {
            $randomHourStart = mt_rand(0, 22);
            $randomHourEnd = $randomHourStart + mt_rand(1, 2); // Ensure event ends after it starts
            $randomDay = mt_rand(1, 6);
            $start = '2024-01-0'.$randomDay.'T' . str_pad($randomHourStart, 2, "0", STR_PAD_LEFT) . ':01:00';
            $end = '2024-01-0'.$randomDay.'T' . str_pad($randomHourEnd, 2, "0", STR_PAD_LEFT) . ':01:00';

            $startDateTime = new DateTime($start, $utcTimeZone);
            $endDateTime = new DateTime($end, $utcTimeZone);

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
        for ($i = 0; $i < 4; $i++) {
            $days[] = (new DateTime('2024-01-01', new DateTimeZone('Europe/Istanbul')))->modify("+$i day");
        }

        $pixPerHour = 40;

        return $this->render('dayview', ['events' => $this->events, 'showDays' => array_values($days), 'pixPerHour' => $pixPerHour]);
    }

}
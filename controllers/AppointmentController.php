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
    {
        if ($userTimeZone === null) {
            // Fallback to Eurpoe/Istanbul
            $userTimeZone = 'Europe/Istanbul';
        }

        $formatter = new IntlDateFormatter(
            'tr_TR',
            IntlDateFormatter::FULL,
            IntlDateFormatter::NONE,
            'Europe/Istanbul',
            IntlDateFormatter::GREGORIAN,
            'EEEE'
        );

        $utcTimeZone = new DateTimeZone('UTC');
        $userTimeZone = new DateTimeZone($userTimeZone);

        // Handmade events example
        $events = [];
        for ($t = 0; $t < 500; $t++) {
            $randomHourStart = mt_rand(0, 22);
            $randomHourEnd = $randomHourStart + mt_rand(1, 2); // Ensure event ends after it starts
            $randomDay = mt_rand(1, 4);
            $events[] = [
                'id' => 'A' . md5($t * 7),
                'title' => "Morning Meeting $t",
                'start' => '2024-01-0'.$randomDay.'T' . str_pad($randomHourStart, 2, "0", STR_PAD_LEFT) . ':01:00', // UTC
                'end' => '2024-01-0'.$randomDay.'T' . str_pad($randomHourEnd, 2, "0", STR_PAD_LEFT) . ':01:00', // UTC
            ];
        }

        foreach ($events as &$event) {
            $startDateTime = new DateTime($event['start'], $utcTimeZone);
            $endDateTime = new DateTime($event['end'], $utcTimeZone);

            $startDateTime->setTimezone($userTimeZone);
            $endDateTime->setTimezone($userTimeZone);

            $event['start'] = $startDateTime->format('c'); // ISO 8601 format
            $event['end'] = $endDateTime->format('c');
            $event['day'] = $formatter->format($startDateTime);
        }

        return $events;
    }

    public function actionEvents($slug)
    {
        return $this->asJson($this->getEvents());
    }

    public function actionView($slug)
    {
        $days = [];
        for ($i = 0; $i < 5; $i++) {
            $days[] = (new DateTime('2024-01-01'))->modify("+$i day");
        }

        Yii::$app->session->setFlash('success', 'Test page with 500 random events. Events retrieved by javascript. Timezone dynamically converted from server (+0) to Istanbul (+3). Feel free to drag events around. Push Redraw to redraw everyhing. Fetch All gets new 500 random events from server without page reload.');

        $pixPerHour = 40;

        return $this->render('dayview', ['events' => $this->events, 'days' => array_values($days), 'pixPerHour' => $pixPerHour]);
    }

}
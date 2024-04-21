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

    public function actionView($slug)
    {
        $events = [
            [
                'title' => 'Morning Meeting',
                'start' => DateTime::createFromFormat('H:i', '03:00'),
                'duration' => 50,
            ],
            [
                'title' => 'Morning Meeting Morning Meeting Morning Meeting Morning Meeting Morning Meeting ',
                'start' => DateTime::createFromFormat('H:i', '05:00'),
                'duration' => 50,
            ],
            [
                'title' => 'Morning Meeting',
                'start' => DateTime::createFromFormat('H:i', '07:00'),
                'duration' => 50,
            ],
            [
                'title' => 'Morning Meeting',
                'start' => DateTime::createFromFormat('H:i', '09:00'),
                'duration' => 50,
            ],
            [
                'title' => 'Morning Meeting',
                'start' => DateTime::createFromFormat('H:i', '11:00'),
                'duration' => 50,
            ],
            [
                'title' => 'Morning Meeting',
                'start' => DateTime::createFromFormat('H:i', '13:00'),
                'duration' => 50,
            ],
            [
                'title' => 'Lunch Break',
                'start' => DateTime::createFromFormat('H:i', '15:30'),
                'duration' => 90,
            ],
        ];
    
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        $pixPerHour = 40;

        return $this->render('view1', ['events' => $events, 'days' => array_values($days), 'pixPerHour' => $pixPerHour]);
    }

}
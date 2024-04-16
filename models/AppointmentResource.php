<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use app\models\query\AppointmentQuery;
use app\models\query\AppointmentResourceQuery;
use app\models\query\ResourceQuery;
use app\components\LogBehavior;


/**
 * @property int         $id
 * @property int         $appointment_id
 * @property int         $resource_id
 * @property string      $created_at
 * @property string|null $deleted_at
 * @property Appointment $appointment
 * @property resource    $resource
 */
class AppointmentResource extends ActiveRecord
{
    use traits\SoftDeleteTrait;

    public static function tableName(): string
    {
        return 'appointments_resources';
    }

    public function rules(): array
    {
        return [
            [['appointment_id', 'resource_id'], 'required'],
            [['appointment_id', 'resource_id'], 'integer'],
            [['created_at', 'deleted_at'], 'safe'],
            [['resource_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resource::class, 'targetAttribute' => ['resource_id' => 'id']],
            [['appointment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Appointment::class, 'targetAttribute' => ['appointment_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'appointment_id' => Yii::t('app', 'Appointment ID'),
            'resource_id' => Yii::t('app', 'Resource ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
        ];
    }

    public function behaviors()
    {
        return [
            'logBehavior' => [
                'class' => LogBehavior::class,
                'eventTypeCreate' => LogBase::EVENT_APPOINTMENT_RESOURCE_ADDED,
                'eventTypeUpdate' => null,
                'eventTypeDelete' => LogBase::EVENT_APPOINTMENT_RESOURCE_DELETED,
            ],
        ];
    }

    public function getAppointment(): ActiveQuery|AppointmentQuery
    {
        return $this->hasOne(Appointment::class, ['id' => 'appointment_id'])->inverseOf('appointmentResources');
    }

    public function getResource(): ActiveQuery|ResourceQuery
    {
        return $this->hasOne(Resource::class, ['id' => 'resource_id'])->inverseOf('appointmentResources');
    }

    public static function find(): AppointmentResourceQuery
    {
        return new AppointmentResourceQuery(get_called_class());
    }
}

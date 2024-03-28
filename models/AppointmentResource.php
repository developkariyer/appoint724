<?php

namespace app\models;

use Yii;

/**
 * @property int $id
 * @property int $appointment_id
 * @property int $resource_id
 * @property string $created_at
 * @property string|null $deleted_at
 *
 * @property Appointment $appointment
 * @property Resource $resource
 */
class AppointmentResource extends \yii\db\ActiveRecord
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


    public function getAppointment(): \yii\db\ActiveQuery|AppointmentQuery
    {
        return $this->hasOne(Appointment::class, ['id' => 'appointment_id'])->inverseOf('appointmentResources');
    }


    public function getResource(): \yii\db\ActiveQuery|ResourceQuery
    {
        return $this->hasOne(Resource::class, ['id' => 'resource_id'])->inverseOf('appointmentResources');
    }


    public static function find(): AppointmentResourceQuery
    {
        return new AppointmentResourceQuery(get_called_class());
    }
}

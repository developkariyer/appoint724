<?php

namespace app\models;

use Yii;

/**
 * @property int                   $id
 * @property int                   $business_id
 * @property string|null           $resource_type
 * @property string                $created_at
 * @property string|null           $updated_at
 * @property string|null           $deleted_at
 * @property AppointmentResource[] $appointmentsResources
 * @property Business              $business
 */
class Resource extends \yii\db\ActiveRecord
{
    use traits\SoftDeleteTrait;

    public static function tableName(): string
    {
        return 'resources';
    }

    public function rules(): array
    {
        return [
            [['business_id'], 'required'],
            [['business_id'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['resource_type'], 'string', 'max' => 45],
            [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => Business::class, 'targetAttribute' => ['business_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'business_id' => Yii::t('app', 'Business ID'),
            'resource_type' => Yii::t('app', 'Resource Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
        ];
    }

    public function getAppointments(): \yii\db\ActiveQuery|AppointmentQuery
    {
        return $this->hasMany(Appointment::class, ['id' => 'appointment_id'])
            ->viaTable(AppointmentResource::tableName(), ['resource_id' => 'id']);
    }

    public function getBusiness(): \yii\db\ActiveQuery|BusinessQuery
    {
        return $this->hasOne(Business::class, ['id' => 'business_id'])->inverseOf('resources');
    }

    public static function find(): ResourceQuery
    {
        return new ResourceQuery(get_called_class());
    }
}

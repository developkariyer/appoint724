<?php

namespace app\models;

use Yii;
use app\models\queries\AppointmentQuery;
use app\models\queries\BusinessQuery;
use app\components\LogBehavior;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;


/**
 * @property int                   $id
 * @property string                $name
 * @property int                   $business_id
 * @property string|null           $resource_type
 * @property string                $created_at
 * @property string|null           $updated_at
 * @property string|null           $deleted_at
 * @property AppointmentResource[] $appointmentsResources
 * @property Business              $business
 */
class Resource extends ActiveRecord
{
    use traits\SoftDeleteTrait;
    use traits\BusinessCacheTrait;

    public static function tableName(): string
    {
        return 'resources';
    }

    public function rules(): array
    {
        return [
            [['name', 'resource_type'], 'safe'],
            [['business_id', 'name'], 'required'],
            [['business_id'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name', 'resource_type'], 'string', 'max' => 255],
            [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => Business::class, 'targetAttribute' => ['business_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => Yii::t('app', 'Resource'),
            'resource_type' => Yii::t('app', 'Resource Type'),
        ];
    }

    public function behaviors(): array
    {
        return [
            'logBehavior' => [
                'class' => LogBehavior::class,
                'eventTypeCreate' => LogBase::EVENT_RESOURCE_CREATED,
                'eventTypeUpdate' => LogBase::EVENT_RESOURCE_UPDATED,
                'eventTypeDelete' => LogBase::EVENT_RESOURCE_DELETED,
            ],
        ];
    }

    /**
     * @throws InvalidConfigException
     */
    public function getAppointments(): ActiveQuery|AppointmentQuery
    {
        return $this->hasMany(Appointment::class, ['id' => 'appointment_id'])
            ->viaTable(AppointmentResource::tableName(), ['resource_id' => 'id']);
    }

    public function getBusiness(): ActiveQuery|BusinessQuery
    {
        return $this->hasOne(Business::class, ['id' => 'business_id'])->inverseOf('resources');
    }

    public static function businessColumns(): array
    {
        return [
            'name',
            'resource_type',
        ];
    }

}

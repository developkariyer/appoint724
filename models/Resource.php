<?php

namespace app\models;

use Yii;
use app\models\query\AppointmentQuery;
use app\models\query\BusinessQuery;
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
            [['name'], 'string', 'max' => 255],
            [['resource_type'], 'string', 'max' => 45],
            [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => Business::class, 'targetAttribute' => ['business_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Resource'),
            'business_id' => Yii::t('app', 'Business ID'),
            'resource_type' => Yii::t('app', 'Resource Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
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

}

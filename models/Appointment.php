<?php

namespace app\models;

use DateTime;
use DateTimeZone;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use app\models\queries\AppointmentQuery;
use app\models\queries\BusinessQuery;
use app\components\LogBehavior;

/**
 * @property int                   $id
 * @property int                   $business_id
 * @property string                $start_time            Always, UTC. So MySQL's date and time related functions cannot be used in queries!
 * @property string                $end_time
 * @property string                $status
 * @property string|null           $created_at
 * @property string|null           $updated_at
 * @property string|null           $deleted_at
 * @property AppointmentResource[] $appointmentsResources
 * @property AppointmentUser[]     $appointmentsUsers
 * @property Business              $business
 */
class Appointment extends ActiveRecord
{
    use traits\SoftDeleteTrait;
    use traits\BusinessCacheTrait;

    public static function tableName(): string
    {
        return 'appointments';
    }

    public function rules(): array
    {
        return [
            [['business_id', 'start_time', 'end_time'], 'required'],
            [['business_id'], 'integer'],
            [['start_time', 'end_time', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['status'], 'string'],
            [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => Business::class, 'targetAttribute' => ['business_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'start_time' => Yii::t('app', 'Start Time'),
            'end_time' => Yii::t('app', 'End Time'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    public function behaviors(): array
    {
        return [
            'logBehavior' => [
                'class' => LogBehavior::class,
                'eventTypeCreate' => LogBase::EVENT_BUSINESS_CREATED,
                'eventTypeUpdate' => LogBase::EVENT_BUSINESS_UPDATED,
                'eventTypeDelete' => LogBase::EVENT_BUSINESS_DELETED,
            ],
        ];
    }

    public function getStartTime()
    {
        return new DateTime($this->start_time, new DateTimeZone('UTC'));
    }

    public function setStartTime(DateTime $dateTime)
    {
        $this->start_time = $dateTime->format('Y-m-d H:i:s');  // Always store back as UTC string
    }

    public function getEndTime()
    {
        return new DateTime($this->end_time, new DateTimeZone('UTC'));
    }

    public function setEndTime(DateTime $dateTime)
    {
        $this->end_time = $dateTime->format('Y-m-d H:i:s');  // Always store back as UTC string
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Ensure UTC datetime strings are correctly set
            $this->start_time = $this->getStartTime()->format('Y-m-d H:i:s');
            $this->end_time = $this->getEndTime()->format('Y-m-d H:i:s');
            return true;
        }
        return false;
    }

    /**
     * @throws InvalidConfigException
     */
    public function getResources(): ActiveQuery
    {
        return $this->hasMany(Resource::class, ['id' => 'resource_id'])
            ->viaTable(AppointmentResource::tableName(), ['appointment_id' => 'id']);
    }

    /**
     * @throws InvalidConfigException
     */
    public function getUsers(): ActiveQuery
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])
            ->viaTable(AppointmentUser::tableName(), ['appointment_id' => 'id']);
    }

    public function getBusiness(): ActiveQuery|BusinessQuery
    {
        return $this->hasOne(Business::class, ['id' => 'business_id'])->inverseOf('appointments');
    }

    public static function find(): AppointmentQuery
    {
        return new AppointmentQuery(get_called_class());
    }
}

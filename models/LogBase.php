<?php

namespace app\models;

use Yii;
use app\models\query\LogBaseQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $user_id
 * @property string $event_type
 * @property string $event
 * @property string $created_at
 */
class LogBase extends ActiveRecord
{

    const EVENT_USER_CREATED = 'user_created';
    const EVENT_USER_UPDATED = 'user_updated';
    const EVENT_USER_DELETED = 'user_deleted';

    const EVENT_USER_AUTH_ADDED = 'user_auth_added';

    const EVENT_USER_BUSINESS_ADDED = 'user_business_added';
    const EVENT_USER_BUSINESS_UPDATED = 'user_business_updated';
    const EVENT_USER_BUSINESS_DELETED = 'user_business_deleted';

    const EVENT_BUSINESS_CREATED = 'business_created';
    const EVENT_BUSINESS_UPDATED = 'business_updated';
    const EVENT_BUSINESS_DELETED = 'business_deleted';

    const EVENT_APPOINTMENT_CREATED = 'appointment_created';
    const EVENT_APPOINTMENT_UPDATED = 'appointment_updated';
    const EVENT_APPOINTMENT_DELETED = 'appointment_deleted';

    const EVENT_RESOURCE_CREATED = 'resource_created';
    const EVENT_RESOURCE_UPDATED = 'resource_updated';
    const EVENT_RESOURCE_DELETED = 'resource_deleted';

    const EVENT_RULE_CREATED = 'rule_created';
    const EVENT_RULE_UPDATED = 'rule_updated';
    const EVENT_RULE_DELETED = 'rule_deleted';

    const EVENT_SERVICE_CREATED = 'service_created';
    const EVENT_SERVICE_UPDATED = 'service_updated';
    const EVENT_SERVICE_DELETED = 'service_deleted';

    const EVENT_APPOINTMENT_RESOURCE_ADDED = 'appointment_resource_added';
    const EVENT_APPOINTMENT_RESOURCE_DELETED = 'appointment_resource_deleted';

    const EVENT_APPOINTMENT_USER_ADDED = 'appointment_user_added';
    const EVENT_APPOINTMENT_USER_DELETED = 'appointment_user_deleted';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['event_type', 'event', 'user_id'], 'required'],
            [['user_id'], 'integer'],
            [['event_type'], 'string'],
            [['event', 'created_at'], 'safe'],
            [
                'event_type', 
                'in', 
                'range' => [
                    self::EVENT_USER_CREATED,
                    self::EVENT_USER_UPDATED,
                    self::EVENT_USER_DELETED,
                    self::EVENT_USER_AUTH_ADDED,
                    self::EVENT_USER_BUSINESS_ADDED,
                    self::EVENT_USER_BUSINESS_UPDATED,
                    self::EVENT_USER_BUSINESS_DELETED,
                    self::EVENT_BUSINESS_CREATED,
                    self::EVENT_BUSINESS_UPDATED,
                    self::EVENT_BUSINESS_DELETED,
                    self::EVENT_APPOINTMENT_CREATED,
                    self::EVENT_APPOINTMENT_UPDATED,
                    self::EVENT_APPOINTMENT_DELETED, 
                    self::EVENT_RESOURCE_CREATED, 
                    self::EVENT_RESOURCE_UPDATED, 
                    self::EVENT_RESOURCE_DELETED, 
                    self::EVENT_RULE_CREATED,
                    self::EVENT_RULE_UPDATED,
                    self::EVENT_RULE_DELETED,
                    self::EVENT_SERVICE_CREATED,
                    self::EVENT_SERVICE_UPDATED,
                    self::EVENT_SERVICE_DELETED,
                    self::EVENT_APPOINTMENT_RESOURCE_ADDED,
                    self::EVENT_APPOINTMENT_RESOURCE_DELETED,
                    self::EVENT_APPOINTMENT_USER_ADDED,
                    self::EVENT_APPOINTMENT_USER_DELETED,
                ]
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'event_type' => Yii::t('app', 'Event Type'),
            'event' => Yii::t('app', 'Event'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    public static function log($event_type, array $data): void
    {
        $log = new static();
        $log->event_type = $event_type;
        $log->event = json_encode($data, JSON_INVALID_UTF8_IGNORE | JSON_PARTIAL_OUTPUT_ON_ERROR);
        $log->user_id = Yii::$app->user->identity->user->id ?? 0;
        $log->save();
    }

    /**
     * {@inheritdoc}
     * @return LogBaseQuery the active query used by this AR class.
     */
    public static function find(): LogBaseQuery
    {
        return new LogBaseQuery(get_called_class());
    }

}

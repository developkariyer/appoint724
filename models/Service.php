<?php

namespace app\models;

use Yii;
use app\models\queries\BusinessQuery;
use app\components\LogBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;


/**
 * @property int         $id            Preset services given to customers. Used for fast appointment setting.
 * @property int         $business_id
 * @property string|null $name
 * @property string|null $resource_type
 * @property string|null $expert_type
 * @property int         $duration
 * @property string      $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 * @property Business    $business
 */
class Service extends ActiveRecord
{
    use traits\SoftDeleteTrait;
    use traits\BusinessCacheTrait;


    public static function tableName(): string
    {
        return 'services';
    }

    public function rules(): array
    {
        return [
            [['business_id', 'duration'], 'required'],
            [['business_id', 'duration'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name', 'resource_type', 'expert_type'], 'string', 'max' => 255],
            [['name', 'business_id', 'deleted_at'], 'unique', 'targetAttribute' => ['name', 'business_id', 'deleted_at'], 'message' => Yii::t('app', 'Service Name must be unique.')],
            [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => Business::class, 'targetAttribute' => ['business_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'business_id' => Yii::t('app', 'Business ID'),
            'name' => Yii::t('app', 'Service Name'),
            'resource_type' => Yii::t('app', 'Resource Type'),
            'expert_type' => Yii::t('app', 'Expert Type'),
            'duration' => Yii::t('app', 'Duration'),
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
                'eventTypeCreate' => LogBase::EVENT_SERVICE_CREATED,
                'eventTypeUpdate' => LogBase::EVENT_SERVICE_UPDATED,
                'eventTypeDelete' => LogBase::EVENT_SERVICE_DELETED,
            ],
        ];
    }

    public function getBusiness(): ActiveQuery|BusinessQuery
    {
        return $this->hasOne(Business::class, ['id' => 'business_id'])->inverseOf('services');
    }

    public static function businessColumns(): array
    {
        return ['name', 'resource_type', 'duration'];
    }

}

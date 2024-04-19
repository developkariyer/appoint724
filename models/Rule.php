<?php

namespace app\models;

use Yii;
use app\models\queries\BusinessQuery;
use app\components\LogBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int         $id
 * @property string      $name
 * @property string      $ruleset
 * @property int         $business_id
 * @property string      $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 * @property Business    $business
 */
class Rule extends ActiveRecord
{
    use traits\SoftDeleteTrait;
    use traits\BusinessCacheTrait;

    public static function tableName(): string
    {
        return 'rules';
    }

    public function rules(): array
    {
        return [
            [['business_id'], 'integer'],
            [['name', 'ruleset', 'business_id'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['name', 'ruleset', 'business_id', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => Business::class, 'targetAttribute' => ['business_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),	       
            'name' => Yii::t('app', 'Rule Name'), 
            'ruleset' => Yii::t('app', 'Rule Set'), 
            'business_id' => Yii::t('app', 'Business ID'),
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
                'eventTypeCreate' => LogBase::EVENT_RULE_CREATED,
                'eventTypeUpdate' => LogBase::EVENT_RULE_UPDATED,
                'eventTypeDelete' => LogBase::EVENT_RULE_DELETED,
            ],
        ];
    }

    public function getBusiness(): ActiveQuery|BusinessQuery
    {
        return $this->hasOne(Business::class, ['id' => 'business_id'])->inverseOf('rules');
    }

}

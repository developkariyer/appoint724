<?php

namespace app\models;

use Yii;

/**
 * @property int         $id
 * @property int         $business_id
 * @property string      $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 * @property Business    $business
 */
class Rule extends \yii\db\ActiveRecord
{
    use traits\SoftDeleteTrait;

    public static function tableName(): string
    {
        return 'rules';
    }

    public function rules(): array
    {
        return [
            [['business_id'], 'required'],
            [['business_id'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => Business::class, 'targetAttribute' => ['business_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'business_id' => Yii::t('app', 'Business ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
        ];
    }

    public function getBusiness(): \yii\db\ActiveQuery|BusinessQuery
    {
        return $this->hasOne(Business::class, ['id' => 'business_id'])->inverseOf('rules');
    }

    public static function find(): RuleQuery
    {
        return new RuleQuery(get_called_class());
    }
}

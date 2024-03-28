<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "services".
 *
 * @property int $id Preset services given to customers. Used for fast appointment setting.
 * @property int $business_id
 * @property string|null $name
 * @property string|null $resource_type
 * @property string|null $expert_type
 * @property int $duration
 * @property string $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Business $business
 */
class Service extends \yii\db\ActiveRecord
{
    use traits\SoftDeleteTrait; 

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'services';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['business_id', 'duration'], 'required'],
            [['business_id', 'duration'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name', 'resource_type', 'expert_type'], 'string', 'max' => 45],
            [['name'], 'unique'],
            [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => Business::class, 'targetAttribute' => ['business_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'business_id' => Yii::t('app', 'Business ID'),
            'name' => Yii::t('app', 'Name'),
            'resource_type' => Yii::t('app', 'Resource Type'),
            'expert_type' => Yii::t('app', 'Expert Type'),
            'duration' => Yii::t('app', 'Duration'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
        ];
    }

    /**
     * Gets query for [[Business]].
     *
     * @return \yii\db\ActiveQuery|BusinessQuery
     */
    public function getBusiness()
    {
        return $this->hasOne(Business::class, ['id' => 'business_id'])->inverseOf('services');
    }

    /**
     * {@inheritdoc}
     * @return ServiceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ServiceQuery(get_called_class());
    }
}

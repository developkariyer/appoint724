<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users_businesses".
 *
 * @property int $id
 * @property int $user_id
 * @property int $business_id
 * @property string $created_at
 * @property string|null $deleted_at
 *
 * @property Business $business
 * @property User $user
 */
class UserBusiness extends \yii\db\ActiveRecord
{
    use traits\SoftDeleteTrait;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users_businesses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'business_id'], 'required'],
            [['user_id', 'business_id'], 'integer'],
            [['created_at', 'deleted_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'user_id' => Yii::t('app', 'User ID'),
            'business_id' => Yii::t('app', 'Business ID'),
            'created_at' => Yii::t('app', 'Created At'),
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
        return $this->hasOne(Business::class, ['id' => 'business_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function addUserToBusiness($userId, $businessId)
    {
        if (!static::findOne(['user_id' => $userId, 'business_id' => $businessId, 'deleted_at' => null])) {
            $userBusiness = new UserBusiness([
                'user_id' => $userId,
                'business_id' => $businessId,
            ]);
            return $userBusiness->save();
        }
        return false;
    }

    /**
     * {@inheritdoc}
     * @return UserBusinessQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserBusinessQuery(get_called_class());
    }
}

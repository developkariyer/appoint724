<?php

namespace app\models;

use Yii;

/**
 * @property int         $id
 * @property int         $user_id
 * @property int         $business_id
 * @property string      $created_at
 * @property string|null $deleted_at
 * @property Business    $business
 * @property User        $user
 */
class UserBusiness extends \yii\db\ActiveRecord
{
    use traits\SoftDeleteTrait;

    const ROLE_ADMIN = 'admin';
    const ROLE_SECRETARY = 'secretary';
    const ROLE_EXPERT = 'expert';
    const ROLE_CUSTOMER = 'customer';

    public static function tableName(): string
    {
        return 'users_businesses';
    }

    public function rules(): array
    {
        return [
            [['user_id', 'business_id'], 'required'],
            [['user_id', 'business_id'], 'integer'],
            [['created_at', 'deleted_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => Business::class, 'targetAttribute' => ['business_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'role' => Yii::t('app', 'Role'),
            'business_id' => Yii::t('app', 'Business ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
        ];
    }

    public function getBusiness(): \yii\db\ActiveQuery|BusinessQuery
    {
        return $this->hasOne(Business::class, ['id' => 'business_id']);
    }

    public function getUser(): \yii\db\ActiveQuery|UserQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function addUserToBusiness($userId, $businessId, $role): bool
    {
        if (!static::findOne(['user_id' => $userId, 'business_id' => $businessId, 'deleted_at' => null])) {
            $userBusiness = new UserBusiness([
                'user_id' => $userId,
                'business_id' => $businessId,
                'role' => $role,
            ]);

            return $userBusiness->save();
        }

        return false;
    }

    public static function find(): UserBusinessQuery
    {
        return new UserBusinessQuery(get_called_class());
    }
}

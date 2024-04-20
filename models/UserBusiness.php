<?php

namespace app\models;

use InvalidArgumentException;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use app\models\queries\UserQuery;
use app\models\queries\BusinessQuery;
use app\components\LogBehavior;


/**
 * @property int         $id
 * @property int         $user_id
 * @property int         $business_id
 * @property string      $created_at
 * @property Business    $business
 * @property User        $user
 */
class UserBusiness extends ActiveRecord
{
    use traits\BusinessCacheTrait;

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
            [['created_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => Business::class, 'targetAttribute' => ['business_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'role' => Yii::t('app', 'Role'),
        ];
    }

    public function behaviors(): array
    {
        return [
            'logBehavior' => [
                'class' => LogBehavior::class,
                'eventTypeCreate' => LogBase::EVENT_USER_BUSINESS_ADDED,
                'eventTypeUpdate' => null,
                'eventTypeDelete' => LogBase::EVENT_USER_BUSINESS_DELETED,
            ],
        ];
    }

    public function reassignUserBusiness($role): bool
    {
        $this->role = $role;
        return $this->save(false, ['role']);
    }

    public static function addUserBusiness($userId, $businessId, $role): bool
    {
        $userBusiness = static::find()->where(['user_id' => $userId, 'business_id' => $businessId])->one();

        if ($userBusiness) {
            throw new InvalidArgumentException('User already assigned to business');
        }
        
        $userBusiness = new UserBusiness([
            'user_id' => $userId,
            'business_id' => $businessId,
            'role' => $role,
        ]);
        return $userBusiness->save();
    }

    public static function deleteUserBusiness($userId, $businessId): bool
    {
        $userBusiness = static::find()->where(['user_id' => $userId, 'business_id' => $businessId])->one();
        if ($userBusiness) {
            return $userBusiness->delete();
        }
        return false;
    }

    public static function exists($business_id, $user_id): bool
    {
        return static::find()
            ->where(['business_id' => $business_id, 'user_id' => $user_id])
            ->exists();
    }    

    public function getBusiness(): ActiveQuery|BusinessQuery
    {
        return $this->hasOne(Business::class, ['id' => 'business_id']);
    }

    public function getUser(): ActiveQuery|UserQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}

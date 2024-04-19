<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use app\models\query\UserBusinessQuery;
use app\models\query\UserQuery;
use app\models\query\BusinessQuery;
use app\components\LogBehavior;


/**
 * @property int         $id
 * @property int         $user_id
 * @property int         $business_id
 * @property string      $created_at
 * @property string|null $deleted_at
 * @property Business    $business
 * @property User        $user
 */
class UserBusiness extends ActiveRecord
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

    public function behaviors(): array
    {
        return [
            'logBehavior' => [
                'class' => LogBehavior::class,
                'eventTypeCreate' => LogBase::EVENT_USER_BUSINESS_ADDED,
                'eventTypeUpdate' => LogBase::EVENT_USER_BUSINESS_UPDATED,
                'eventTypeDelete' => LogBase::EVENT_USER_BUSINESS_DELETED,
            ],
        ];
    }

    public function getBusiness(): ActiveQuery|BusinessQuery
    {
        return $this->hasOne(Business::class, ['id' => 'business_id']);
    }

    public function getUser(): ActiveQuery|UserQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function addUserBusiness($userId, $businessId, $role): bool
    {
        $userBusiness = static::findOne(['user_id' => $userId, 'business_id' => $businessId]);

        if ($userBusiness) {
            if($userBusiness->deleted_at) {
                $userBusiness->deleted_at = null;
                return $userBusiness->save();
            } else {
                return false;
            }
        }
        $userBusiness = new UserBusiness([
            'user_id' => $userId,
            'business_id' => $businessId,
            'role' => $role,
        ]);
        return $userBusiness->save();
    }

    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);
        $cacheKey = 'business_'.$this->business_id.'_stats';
        Yii::$app->cache->delete($cacheKey);
    }

    public static function deleteUserBusiness($userId, $businessId): bool
    {
        if (!Yii::$app->user->identity->user->superadmin && Yii::$app->user->identity->user->id === $userId) {
            return false;
        }
        
        $userBusiness = static::findOne(['user_id' => $userId, 'business_id' => $businessId, 'deleted_at' => null]);
        if ($userBusiness) {
            return $userBusiness->softDelete();
        }
        return false;
    }

    public static function find(): UserBusinessQuery
    {
        return new UserBusinessQuery(get_called_class());
    }

    public static function exists($business_id, $user_id): bool
    {
        return static::find()
            ->where(['business_id' => $business_id, 'user_id' => $user_id, 'deleted_at' => null])
            ->exists();
    }    

}

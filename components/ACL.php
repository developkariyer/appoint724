<?php 

namespace app\components;

use app\models\UserBusiness;
use Yii;

class ACL
{
    public static function isGuest()
    {
        return Yii::$app->user->isGuest;
    }

    public static function isUserLoggedIn($user_id)
    {
        return !self::isGuest() ? $user_id == Yii::$app->user->identity->user->id : false;
    }

    public static function isSuperAdmin()
    {
        return !self::isGuest() && Yii::$app->user->identity->user->superadmin;
    }

    public static function canUserAdd($business_id)
    {
        return self::isSuperAdmin() ||        
            UserBusiness::find()->where([
                'business_id' => $business_id,
                'user_id' => Yii::$app->user->identity->user->id,
                'role' => ['admin', 'secretary'],
            ])->exists();
    }

    public static function canUserUpdate($user_id)
    {
        if (self::isSuperAdmin() || self::isUserLoggedIn($user_id)) {
            return true;
        };

        $userBusinesses = UserBusiness::find()
            ->where([
                'user_id' => Yii::$app->user->identity->user->id,
                'role' => ['admin', 'secretary'],
                'deleted_at' => null
            ])->all();

        foreach ($userBusinesses as $userBusiness) {
            $targetUserBusinesses = UserBusiness::find()
                ->where([
                    'user_id' => $user_id,
                    'business_id' => $userBusiness->business_id,
                ])->all();

            if (!empty($targetUserBusinesses)) {
                if ($userBusiness->role === 'admin') {
                    return true; 
                }

                foreach ($targetUserBusinesses as $targetUserBusiness) {
                    if ($targetUserBusiness->role !== 'admin') {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public static function canBusinessCreate()
    {
        return !self::isGuest() && (
            self::isSuperAdmin() ||
            Yii::$app->user->identity->user->remainingBusinessCount
        );
    }

    public static function canBusinessUpdateDelete($business_id)
    {
        return !self::isGuest() && (
            self::isSuperAdmin() ||
            UserBusiness::find()->where([
                'user_id' => Yii::$app->user->identity->user->id,
                'role' => 'admin',
                'business_id' => $business_id,
            ])->exists()
        );
    }

    public static function canBusiness($business_id)
    {
        return !self::isGuest() && (
            self::isSuperAdmin() ||
            UserBusiness::find()->where([
                'user_id' => Yii::$app->user->identity->user->id,
                'role' => ['admin', 'secretary'],
                'business_id' => $business_id,
            ])->exists()
        );
    }

    public static function canBusinessUserChange($business_id, $user_id)
    {
        return !self::isGuest() && (
            self::isSuperAdmin() ||
            UserBusiness::find()->where([
                'user_id' => Yii::$app->user->identity->user->id,
                'role' => 'admin',
                'business_id' => $business_id,
            ])->exists() || 
            UserBusiness::find()->where([
                'user_id' => $user_id,
                'role' => ['not', 'admin'],
                'business_id' => $business_id,
            ])->exists()            
        );
    }

}
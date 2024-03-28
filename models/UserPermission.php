<?php

namespace app\models;

use Yii;

/**
 * @property int         $id
 * @property int         $user_id
 * @property string      $permission
 * @property string      $created_at
 * @property string|null $deleted_at
 * @property User        $user
 */
class UserPermission extends \yii\db\ActiveRecord
{
    use traits\SoftDeleteTrait;

    public static function tableName(): string
    {
        return 'users_permissions';
    }

    public function rules(): array
    {
        return [
            [['user_id', 'permission'], 'required'],
            [['user_id'], 'integer'],
            [['created_at', 'deleted_at'], 'safe'],
            [['permission'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'permission' => Yii::t('app', 'Permission'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
        ];
    }

    public static function hasPermission($userId, $permission): bool
    {
        return self::find()->where(['user_id' => $userId, 'permission' => $permission])->exists();
    }

    public static function givePermissionToUser($userId, $permission): bool
    {
        if (self::hasPermission($userId, $permission)) {
            return true;
        }
        $userPermission = new UserPermission();
        $userPermission->user_id = $userId;
        $userPermission->permission = $permission;

        return $userPermission->save();
    }

    public static function find(): UserPermissionQuery
    {
        return new UserPermissionQuery(get_called_class());
    }
}
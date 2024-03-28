<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users_permissions".
 *
 * @property int $id
 * @property int $user_id
 * @property string $permission
 * @property string $created_at
 * @property string|null $deleted_at
 *
 * @property User $user
 */
class UserPermission extends \yii\db\ActiveRecord
{
    use traits\SoftDeleteTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users_permissions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'permission'], 'required'],
            [['user_id'], 'integer'],
            [['created_at', 'deleted_at'], 'safe'],
            [['permission'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'permission' => Yii::t('app', 'Permission'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
        ];
    }

    /**
     * Query for permission
     *
     * @return bool
     */
    public static function hasPermission($userId, $permission)
    {
        return self::find()->where(['user_id'=> $userId,'permission'=> $permission])->exists();
    }

    public static function givePermissionToUser($userId, $permission) 
    {
        if (self::hasPermission($userId, $permission)) { 
            return true; 
        }
        $userPermission = new UserPermission();
        $userPermission->user_id = $userId;
        $userPermission->permission = $permission;
        return $userPermission->save();        
    }

    /**
     * {@inheritdoc}
     * @return UserPermissionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserPermissionQuery(get_called_class());
    }
}

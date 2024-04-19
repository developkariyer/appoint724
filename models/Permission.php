<?php

namespace app\models;

use Yii;
use app\models\query\UserQuery;
use app\models\query\BusinessQuery;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int         $id
 * @property int         $user_id
 * @property string      $permission
 * @property string      $created_at
 * @property string|null $deleted_at
 * @property User        $user
 * @property Business    $business
 */
class Permission extends ActiveRecord
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

     public function getBusiness(): ActiveQuery|BusinessQuery
    {
        return $this->hasOne(Business::class, ['id' => 'business_id'])->inverseOf('permissions');
    }

    public function getUser(): ActiveQuery|UserQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id'])->inverseOf('permissions');
    }

    public static function hasPermission($userId, $permission): bool
    {
        return self::find()->where(['user_id' => $userId, 'permission' => $permission])->exists();
    }

}

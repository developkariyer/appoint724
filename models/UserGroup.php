<?php

namespace app\models;

use Yii;

/**
 * @property int         $id
 * @property int         $user_id
 * @property string      $group
 * @property string      $created_at
 * @property string|null $deleted_at
 * @property User        $user
 */
class UserGroup extends \yii\db\ActiveRecord
{
    use traits\SoftDeleteTrait;

    public static function tableName(): string
    {
        return 'users_groups';
    }

    public function rules(): array
    {
        return [
            [['user_id', 'group'], 'required'],
            [['user_id'], 'integer'],
            [['created_at', 'deleted_at'], 'safe'],
            [['group'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'group' => Yii::t('app', 'Group'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
        ];
    }

    public static function isUserInGroup($userId, $group): bool
    {
        return self::find()->where(['user_id' => $userId, 'group' => $group])->exists();
    }

    public static function addUserToGroup($userId, $group): bool
    {
        if (self::isUserInGroup($userId, $group)) {
            return true;
        }
        $userGroup = new UserGroup();
        $userGroup->user_id = $userId;
        $userGroup->group = $group;

        return $userGroup->save();
    }

    public static function find(): UserGroupQuery
    {
        return new UserGroupQuery(get_called_class());
    }
}

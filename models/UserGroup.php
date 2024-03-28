<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users_groups".
 *
 * @property int $id
 * @property int $user_id
 * @property string $group
 * @property string $created_at
 * @property string|null $deleted_at
 *
 * @property User $user
 */
class UserGroup extends \yii\db\ActiveRecord
{
    use traits\SoftDeleteTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users_groups';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'group'], 'required'],
            [['user_id'], 'integer'],
            [['created_at', 'deleted_at'], 'safe'],
            [['group'], 'string', 'max' => 255],
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
            'group' => Yii::t('app', 'Group'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
        ];
    }

    public static function isUserInGroup($userId, $group) {
        return self::find()->where(['user_id'=> $userId,'group'=> $group])->exists();
    }

    public static function addUserToGroup($userId, $group) {
        if (self::isUserInGroup($userId, $group)) { 
            return true; 
        }
        $userGroup = new UserGroup();
        $userGroup->user_id = $userId;
        $userGroup->group = $group;
        return $userGroup->save();
    }

    /**
     * {@inheritdoc}
     * @return UserGroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserGroupQuery(get_called_class());
    }
}

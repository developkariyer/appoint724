<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "logins".
 *
 * @property int $id
 * @property string $ip_address
 * @property string|null $user_agent
 * @property string $id_type
 * @property string $identifier
 * @property int|null $user_id
 * @property string $date
 * @property int $success
 *
 * @property User $user
 */
class Login extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'logins';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ip_address', 'id_type', 'identifier', 'success'], 'required'],
            [['user_id', 'success'], 'integer'],
            [['date'], 'safe'],
            [['ip_address', 'user_agent', 'id_type', 'identifier'], 'string', 'max' => 255],
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
            'ip_address' => Yii::t('app', 'Ip Address'),
            'user_agent' => Yii::t('app', 'User Agent'),
            'id_type' => Yii::t('app', 'Id Type'),
            'identifier' => Yii::t('app', 'Identifier'),
            'user_id' => Yii::t('app', 'User ID'),
            'date' => Yii::t('app', 'Date'),
            'success' => Yii::t('app', 'Success'),
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id'])->inverseOf('logins');
    }

    /**
     * {@inheritdoc}
     * @return LoginQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LoginQuery(get_called_class());
    }
}

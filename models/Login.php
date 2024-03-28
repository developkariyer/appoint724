<?php

namespace app\models;

use Yii;

/**
 * @property int         $id
 * @property string      $ip_address
 * @property string|null $user_agent
 * @property string      $id_type
 * @property string      $identifier
 * @property int|null    $user_id
 * @property string      $date
 * @property int         $success
 * @property User        $user
 */
class Login extends \yii\db\ActiveRecord
{
    public static function tableName(): string
    {
        return 'logins';
    }

    public function rules(): array
    {
        return [
            [['ip_address', 'id_type', 'identifier', 'success'], 'required'],
            [['user_id', 'success'], 'integer'],
            [['date'], 'safe'],
            [['ip_address', 'user_agent', 'id_type', 'identifier'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
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

    public function getUser(): \yii\db\ActiveQuery|UserQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id'])->inverseOf('logins');
    }

    public static function find(): LoginQuery
    {
        return new LoginQuery(get_called_class());
    }
}
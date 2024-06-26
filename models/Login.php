<?php

namespace app\models;

use Yii;
use app\models\queries\UserQuery;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;


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
class Login extends ActiveRecord
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
        ];
    }

    public function getUser(): ActiveQuery|UserQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id'])->inverseOf('logins');
    }

    public static function log($id_type, $identifier, $success): void
    {
        $login = new self();
        $login->ip_address = Yii::$app->request->userIP;
        $login->user_agent = Yii::$app->request->userAgent;
        $login->id_type = $id_type;
        $login->identifier = $identifier;
        $login->user_id = Yii::$app->user->id;
        $login->date = date('Y-m-d H:i:s');
        $login->success = $success;
        $login->save();
    }
}

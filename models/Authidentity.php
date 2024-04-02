<?php

namespace app\models;

use Yii;

/**
 * @property int         $id
 * @property int         $user_id
 * @property string      $type         // enum: email, gsm, token, authkey
 * @property string      $secret       // password for email, smspin for gsm, token for token and authkey (all hashed)
 * @property string|null $expires      // expiration time in DATETIME format
 * @property string|null $extra
 * @property int         $force_reset
 * @property string|null $last_used_at
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property User        $user
 */
class Authidentity extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    //use traits\SoftDeleteTrait; // used for soft deletes but this model does not require this feature

    public static function tableName(): string
    {
        return 'authidentities';
    }

    public function rules(): array
    {
        return [
            [['user_id', 'type', 'secret'], 'required'],
            [['user_id', 'force_reset'], 'integer'],
            [['type', 'extra'], 'string'],
            [['expires', 'last_used_at', 'created_at', 'updated_at'], 'safe'],
            [['secret'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'type' => Yii::t('app', 'Type'),
            'secret' => Yii::t('app', 'Secret'),
            'expires' => Yii::t('app', 'Expires'),
            'extra' => Yii::t('app', 'Extra'),
            'force_reset' => Yii::t('app', 'Force Reset'),
            'last_used_at' => Yii::t('app', 'Last Used At'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getUser(): \yii\db\ActiveQuery|UserQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id'])->inverseOf('authidentities');
    }

    public static function find(): AuthidentityQuery
    {
        return new AuthidentityQuery(get_called_class());
    }

    public static function findIdentity($id): Authidentity|null
    {   // Function required by Yii2 User interface
        return static::find()
            ->where(['id' => $id])
            ->andWhere(['>', 'expires', new \yii\db\Expression('NOW()')])
            ->one();
    }

    public static function findIdentityByAccessToken($token, $type = null): Authidentity|null
    {   // Function required by Yii2 User interface
        $candidates = Authidentity::find()
            ->where(['type' => 'remember_me'])
            ->andWhere(['>', 'expires', new \yii\db\Expression('NOW()')])
            ->all();

        foreach ($candidates as $candidate) {
            if (Yii::$app->security->validatePassword($token, $candidate->secret)) {
                return $candidate;
            }
        }

        return null;
    }

    public static function findIdentityByEmailToken($token): Authidentity|null
    {
        $candidates = Authidentity::find()
            ->where(['type' => 'email_token'])
            ->andWhere(['>', 'expires', new \yii\db\Expression('NOW()')])
            ->all();

        foreach ($candidates as $candidate) {
            if (Yii::$app->security->validatePassword($token, $candidate->secret)) {
                return $candidate;
            }
        }

        return null;
    }

    public static function findIdentityByEmail($email): Authidentity|null
    {
        if ($user = User::find()->where(['email' => $email])->one()) {
            return $user->getAuthIdentities()
                ->where(['type' => 'password'])
                ->andWhere(['>', 'expires', new \yii\db\Expression('NOW()')])
                ->one();
        }

        return null;
    }

    public static function findIdentityByGsm($gsm): Authidentity|null
    {
        if ($user = User::find()->where(['gsm' => $gsm])->one()) {
            return $user->getAuthIdentities()
                ->where(['type' => 'sms_pin'])
                ->andWhere(['>', 'expires', new \yii\db\Expression('NOW()')])
                ->one();
        }

        return null;
    }

    public function getId(): int|null
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey(): string|null
    {
        return $this->secret;
    }

    public function validateAuthKey($authKey): bool
    {
        return Yii::$app->security->validatePassword($authKey, $this->getAuthKey());
    }

    public function validatePassword($password): bool
    {
        if (Yii::$app->security->validatePassword($password, $this->secret)) {
            return true;
        }

        return false;
    }

    public static function generateEmailToken($email): string|null
    {
        if ($user = User::findOne(['email' => $email])) {
            $token = Yii::$app->security->generateRandomString();
            $hash = Yii::$app->security->generatePasswordHash($token);
            $authIdentity = new self([
                'user_id' => $user->id,
                'type' => 'email_token',
                'secret' => $hash,
                'expires' => date('Y-m-d H:i:s', strtotime('+10 minutes')), // Sets expires 10 minutes from now
            ]);
            if ($authIdentity->save()) {
                return $token; // For the purpose of sending it via email
            }
        }

        return null; // failure
    }

    public static function generateSmsPin($gsm): string|null
    {
        if ($user = User::findOne(['gsm' => $gsm])) {
            $code = sprintf('%06d', random_int(0, 999999));
            $hash = Yii::$app->security->generatePasswordHash($code);
            $authIdentity = new self([
                'user_id' => $user->id,
                'type' => 'sms_pin',
                'secret' => $hash,
                'expires' => date('Y-m-d H:i:s', strtotime('+2 minutes')), // Sets expires 2 minutes from now
            ]);
            if ($authIdentity->save()) {
                return $code; // For the purpose of sending it via SMS
            }
        }

        return null; // Handle failure
    }
}

<?php

namespace app\models;

use Yii;

/**
 * @property int         $id
 * @property int         $user_id
 * @property string      $type         // enum: email, gsm, token, authkey
 * @property string|null $name         // email for email, gsm for gsm, null for token and authkey
 * @property string      $secret       // password for email, smspin for gsm, token for token and authkey (all hashed)
 * @property string|null $secret2      // authkey for authkey (hashed)
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
    use traits\SoftDeleteTrait;

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
            [['name', 'secret', 'secret2'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'type' => Yii::t('app', 'Type'),
            'name' => Yii::t('app', 'Name'),
            'secret' => Yii::t('app', 'Secret'),
            'secret2' => Yii::t('app', 'Secret2'),
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

    public static function findIdentity($id): User|null
    {
        $identity = static::find()
            ->where(['id' => $id])
            ->andWhere(['or', ['expires' => null], ['>', 'expires', new \yii\db\Expression('NOW()')]])
            ->one();
        if ($identity) {
            return $identity->user;
        }

        return null;
    }

    public static function findIdentityByAccessToken($token, $type = null): User|null
    {
        $candidates = Authidentity::find()
            ->where(['type' => 'token'])
            ->andWhere(['>', 'expires', new \yii\db\Expression('NOW()')])
            ->all();

        foreach ($candidates as $candidate) {
            if (Yii::$app->security->validatePassword($token, $candidate->secret)) {
                $candidate->expires = new \yii\db\Expression('NOW()');
                $candidate->save(false);

                return $candidate->user;
            }
        }

        return null;
    }

    public static function findByUsername($username): Authidentity|null
    {
        return static::find()
            ->where(['name' => $username])
            ->andWhere(['or', ['type' => 'email'], ['type' => 'gsm']])
            ->andWhere(['or', ['expires' => null], ['>', 'expires', new \yii\db\Expression('NOW()')]])
            ->one();
    }

    public function getId(): int|null
    {
        return $this->user->getPrimaryKey();
    }

    public function getAuthKey(): string|null
    {
        return $this->secret2; // Using 'secret2' as the authKey
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password): bool
    {
        if (Yii::$app->security->validatePassword($password, $this->secret)) {
            return true;
        }

        return false;
    }

    public static function validateEmailPassword($email, $password): User|null
    {
        $candidates = Authidentity::find()
            ->where(['type' => 'email', 'name' => $email])
            ->andWhere(['or', ['expires' => null], ['>', 'expires', new \yii\db\Expression('NOW()')]])
            ->all();
        foreach ($candidates as $candidate) {
            if (Yii::$app->security->validatePassword($password, $candidate->secret)) {
                return $candidate->user;
            }
        }

        return null;
    }

    public static function validateGsmPin($gsm, $pin): User|null
    {
        $candidates = Authidentity::find()
            ->where(['type' => 'gsm', 'name' => $gsm])
            ->andWhere(['>', 'expires', new \yii\db\Expression('NOW()')])
            ->all();
        foreach ($candidates as $candidate) {
            if (Yii::$app->security->validatePassword($pin, $candidate->secret)) {
                $user = $candidate->user;
                $candidate->delete();

                return $user;
            }
        }

        return null;
    }

    public static function generateEmailToken($email): string|null
    {
        $user = User::findOne(['email' => $email]);
        if (!$user) {
            return null; // failure: User not found
        }
        $token = Yii::$app->security->generateRandomString();
        $hash = Yii::$app->security->generatePasswordHash($token);
        $authIdentity = new self([
            'user_id' => $user->id,
            'name' => $email,
            'type' => 'token',
            'secret' => $hash,
            'expires' => date('Y-m-d H:i:s', strtotime('+10 minutes')), // Sets expires 10 minutes from now
        ]);
        if ($authIdentity->save()) {
            return $token; // For the purpose of sending it via email
        }

        return null; // failure
    }

    public static function generateGsmCode($gsm): string|null
    {
        $user = User::findOne(['gsm' => $gsm]);
        if (!$user) {
            return null; // User not found
        }
        $code = sprintf('%06d', random_int(0, 999999));
        $hash = Yii::$app->security->generatePasswordHash($code);
        $authIdentity = new self([
            'user_id' => $user->id,
            'name' => $gsm,
            'type' => 'gsm',
            'secret' => $hash,
            'expires' => date('Y-m-d H:i:s', strtotime('+2 minutes')), // Sets expires 2 minutes from now
        ]);
        if ($authIdentity->save()) {
            return $code; // For the purpose of sending it via SMS
        }

        return null; // Handle failure
    }
}

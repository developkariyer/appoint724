<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * @property User|null $user
 */
class LoginForm extends Model
{
    public $password;
    public $sms;
    public $gsm;
    public $email;
    public $emaillink;
    public $rememberMe = true;

    private $_user = null;

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        return $scenarios;
    }

    public function rules(): array
    {
        return [
            [['email', 'gsm', 'emaillink', 'sms'], 'validateLoginForm', 'skipOnEmpty' => false, 'skipOnError' => false],
            [['email', 'gsm', 'emaillink', 'sms'], 'safe'], // Keep other fields safe if necessary
        ];
    }

    public function validateLoginForm($attribute, $params, $validator, $current)
    {
        $this->clearErrors();
        if (empty($this->email) && empty($this->gsm) && empty($this->emaillink) && empty($this->sms)) {
            $this->addError($attribute, 'At least one of Email, GSM, Email Link, or SMS must be provided.');
        }
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'E-mail not registered or invalid password.');
            }
        }
    }

    public function login(): bool
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    public function getUser(): AuthIdentity|null
    {
        if (is_null($this->_user)) {
            $this->_user = Authidentity::findIdentityByEmail($this->email);
        }

        return $this->_user;
    }
}

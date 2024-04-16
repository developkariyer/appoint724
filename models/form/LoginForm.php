<?php

namespace app\models\form;

use Yii;
use yii\base\Model;
use app\models\Authidentity;
use app\models\Login;

/**
 * @property mixed $scenariodesc
 */
class LoginForm extends Model
{
    const SCENARIO_PASSWORD = 'password';
    const SCENARIO_SMS_REQUEST = 'sms_request';
    const SCENARIO_SMS_VALIDATE = 'sms_validate';
    const SCENARIO_LINK = 'link';
    const SCENARIO_OTHER = 'other';

    public $password;
    public $gsm;
    public $email;
    public $emaillink;
    public $smsotp;

    private $_user = null;

    public function scenarios(): array
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_PASSWORD] = ['email', 'password'];
        $scenarios[self::SCENARIO_SMS_REQUEST] = ['gsm'];
        $scenarios[self::SCENARIO_SMS_VALIDATE] = ['gsm', 'smsotp'];
        $scenarios[self::SCENARIO_LINK] = ['emaillink'];
        $scenarios[self::SCENARIO_OTHER] = [];
        return $scenarios;
    }

    public function attributeLabels(): array
    {
        return [
            'email' => Yii::t('app', 'E-mail'),
            'gsm' => Yii::t('app', 'GSM Number'),
            'emaillink' => Yii::t('app', 'E-mail'),
            'sms' => Yii::t('app', 'SMS'),
            'password' => Yii::t('app', 'Password'),
            'smsotp' => Yii::t('app', 'OTP'),
        ];
    }

    public function getScenariodesc(): string
    {
        $scenariodescs = [
            self::SCENARIO_PASSWORD => Yii::t('app', 'Login with Password'),
            self::SCENARIO_SMS_REQUEST => Yii::t('app', 'Request Login with SMS'),
            self::SCENARIO_SMS_VALIDATE => Yii::t('app', 'Login with SMS'),
            self::SCENARIO_LINK => Yii::t('app', 'Login with Link'),
            self::SCENARIO_OTHER => Yii::t('app', 'Other'),
        ];
        return $this->scenario ? $scenariodescs[$this->scenario] : $scenariodescs[self::SCENARIO_PASSWORD];
    }

    public function rules(): array
    {
        return [
            [['email', 'password'], 'required', 'on' => self::SCENARIO_PASSWORD],
            [['gsm'], 'required', 'on' => [self::SCENARIO_SMS_REQUEST, self::SCENARIO_SMS_VALIDATE]],
            [['smsotp'], 'required', 'on' => self::SCENARIO_SMS_VALIDATE],
            [['emaillink'], 'required', 'on' => self::SCENARIO_LINK],
            [['email', 'emaillink'], 'email'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app', 'E-mail not registered or invalid password.'));
            }
        }
    }

    // Define your login logic for each scenario in the login() method or create separate methods for each.
    public function login(): bool
    {
        if ($this->validate()) {
            switch ($this->scenario) {
                case self::SCENARIO_PASSWORD:
                    $authidentity = Authidentity::findIdentityByEmail($this->email);
                    if ($authidentity) {
                        if ($authidentity->validatePassword($this->password)) {
                            $retval = Yii::$app->user->login($authidentity, 3600 * 24 * 30);
                            Login::log($this->scenario, $this->email, 1);
                            return $retval;
                        } else {
                            $this->addError('password', 'Invalid e-mail or password.');
                        }
                    } else {
                        $this->addError('email', 'Invalid e-mail or password.');
                    }
                    break;
                case self::SCENARIO_SMS_VALIDATE:
                    $authidentity = Authidentity::findIdentityByGsm($this->gsm);
                    if ($authidentity && $authidentity->validatePassword($this->smsotp)) {
                        $authidentity->expires = date('Y-m-d H:i:s', strtotime('+3 seconds'));
                        $authidentity->save(false);
                        if (!$authidentity->user->gsmverified) {
                            $authidentity->user->gsmverified = 1;
                            $authidentity->user->save(false);
                        }
                        $retval = Yii::$app->user->login($authidentity, 3600 * 24 * 30);
                        Login::log($this->scenario, $this->gsm, 1);
                        return $retval;
                    }
                    $this->addError('smsotp', 'Invalid OTP or GSM number.');
                    break;
                case self::SCENARIO_LINK:
                case self::SCENARIO_SMS_REQUEST:
                    Login::log($this->scenario, $this->email, 0);
                    return false;
            }
        }
        Login::log($this->scenario, $this->email, 0);
        return false;
    }

    public function getUser()
    {
        if ($this->_user === null) {
            // Assuming findIdentityByEmail can handle null email gracefully
            $this->_user = AuthIdentity::findIdentityByEmail($this->email);
        }
        return $this->_user;
    }
}

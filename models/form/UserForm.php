<?php

namespace app\models\form;

use Yii;
use yii\base\Model;
use app\models\User;

class UserForm extends Model
{
    use \app\models\traits\UserTrait;

    const SCENARIO_REGISTER = 'register';
    const SCENARIO_PASSWORD = 'password';
    const SCENARIO_UPDATE = 'update';
    public $first_name;
    public $last_name;
    public $gsm;
    public $email;
    public $tcno;
    public $dogum_yili;
    public $password;
    public $password_repeat;
    public $password_old;

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_PASSWORD] = ['password', 'password_repeat', 'password_old'];
        $scenarios[self::SCENARIO_UPDATE] = ['first_name', 'last_name', 'gsm', 'email', 'tcno', 'dogum_yili'];
        $scenarios[self::SCENARIO_REGISTER] = ['first_name', 'last_name', 'gsm', 'email', 'tcno', 'dogum_yili', 'password', 'password_repeat'];
        return $scenarios;
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return array_merge($this->commonRules(), 
        [
            [['first_name', 'last_name', 'gsm', 'email', 'tcno', 'dogum_yili'], 'required', 'on' => [self::SCENARIO_REGISTER, self::SCENARIO_UPDATE]],
            [['password', 'password_repeat'], 'string', 'min' => 8],
            [['password', 'password_repeat'], 'required', 'on' => [self::SCENARIO_REGISTER, self::SCENARIO_PASSWORD]],
            [['password_repeat'], 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('app', 'Passwords do not match.'), 'on' => [self::SCENARIO_REGISTER, self::SCENARIO_PASSWORD]],
        ]);
    }
    
/*    public function validatePassword($attribute, $params): void
    {
        $user = Yii::$app->user;
        if (!$user || !$user->validatePassword($this->password_old)) {
            $this->addError($attribute, Yii::t('app', 'Incorrect password.'));
        }
    }
*/
    public function attributeLabels(): array
    {
        $userModel = new User();
        return array_merge($userModel->attributeLabels(),[
            'password_old' => Yii::t('app', 'Old Password'),
            'password' => Yii::t('app', 'Password'),
            'password_repeat' => Yii::t('app', 'Password Repeat'),
        ]);
    }

}

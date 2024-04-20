<?php

namespace app\models;

use Yii;
use app\models\queries\UserQuery;
use app\models\queries\BusinessQuery;
use app\components\LogBehavior;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;


/**
 * @property int              $id
 * @property string|null      $status
 * @property string|null      $status_message
 * @property int              $gsmverified
 * @property int              $emailverified
 * @property int              $tcnoverified
 * @property string|null      $last_active
 * @property string|null      $created_at
 * @property string|null      $updated_at
 * @property string|null      $deleted_at
 * @property string           $first_name
 * @property string           $last_name
 * @property string|null      $tcno
 * @property string           $gsm
 * @property string           $language
 * @property string           $fullname
 * @property int              $dogum_yili
 * @property Appointment[]    $appointments
 * @property Authidentity[]   $authidentities
 * @property Login[]          $logins
 * @property Business[]       $businesses
 * @property Permission[]     $permissions
 * @property int              $remainingBusinessCount
 */
class User extends ActiveRecord
{
    use traits\SoftDeleteTrait;
    use traits\UserTrait;

    public static function tableName(): string
    {
        return 'users';
    }

    public function behaviors(): array
    {
        return [
            'logBehavior' => [
                'class' => LogBehavior::class,
                'eventTypeCreate' => LogBase::EVENT_USER_CREATED,
                'eventTypeUpdate' => LogBase::EVENT_USER_UPDATED,
                'eventTypeDelete' => LogBase::EVENT_USER_DELETED,
            ],
        ];
    }

    public function rules(): array
    {
        return array_merge($this->commonRules(), 
        [
            [['last_active', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['first_name', 'last_name', 'gsm', 'email'], 'required'],
            [['first_name', 'last_name', 'gsm', 'email', 'tcno', 'dogum_yili', 'tcnoverified', 'gsmverified', 'emailverified'], 'safe'],
        ]);
    }

    public function attributeLabels(): array
    {
        return [
            'tcno' => Yii::t('app', 'TC Identity Number'),
            'gsm' => Yii::t('app', 'GSM Number'),
            'email' => Yii::t('app', 'E-mail'),
            'dogum_yili' => Yii::t('app', 'Year of Birth'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'fullname' => Yii::t('app', 'Full Name'),
            'myGsm' => Yii::t('app', 'GSM Number'),
        ];
    }

    /**
     * @throws InvalidConfigException
     */
    public function getAppointments(): ActiveQuery
    {
        return $this->hasMany(Appointment::class, ['id' => 'appointment_id'])
            ->viaTable(AppointmentUser::tableName(), ['user_id' => 'id']);
    }

    public function getAuthidentities(): ActiveQuery
    {
        return $this->hasMany(Authidentity::class, ['user_id' => 'id'])->inverseOf('user');
    }

    public function getLogins(): ActiveQuery
    {
        return $this->hasMany(Login::class, ['user_id' => 'id'])->inverseOf('user');
    }

    /**
     * @throws InvalidConfigException
     */
    public function getBusinesses(): ActiveQuery|BusinessQuery
    {
        return $this->hasMany(Business::class, ['id' => 'business_id'])
            ->viaTable(UserBusiness::tableName(), ['user_id' => 'id']);
    }

    public function getUserBusiness(): ActiveQuery
    {
        return $this->hasMany(UserBusiness::class, ['user_id' => 'id']);
    }

    public function getBusinessesByRole($role): ActiveQuery|BusinessQuery
    {
        return $this->hasMany(Business::class, ['id' => 'business_id'])
            ->viaTable(UserBusiness::tableName(), [
                'user_id' => 'id',
                function($query) use ($role) { $query->andWhere(['role' => $role]); }
            ]
        );
    }

    public function getPermissions(): ActiveQuery
    {
        return $this->hasMany(Permission::class, ['user_id' => 'id'])->inverseOf('user');
    }

    public static function find(): UserQuery
    {
        return new UserQuery(get_called_class());
    }

    public static function findforauth($condition): ActiveRecord|User|null
    {
        return User::find()->where($condition)->one();
    }

    public function getMyGsm(): string
    {
        return preg_replace("/^(\d{3})(\d{3})(\d{4})$/", "($1) $2 $3", $this->gsm);
    }
}

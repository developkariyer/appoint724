<?php

namespace app\models;

use Yii;

/**
 * @property int              $id
 * @property string|null      $status
 * @property string|null      $status_message
 * @property int              $gsmverified
 * @property int              $emailverified
 * @property string|null      $last_active
 * @property string|null      $created_at
 * @property string|null      $updated_at
 * @property string|null      $deleted_at
 * @property string           $first_name
 * @property string           $last_name
 * @property string|null      $tcno
 * @property string           $gsm
 * @property string           $language
 * @property Appointment[]    $appointments
 * @property Authidentity[]   $authidentities
 * @property Login[]          $logins
 * @property Business[]       $Businesses
 * @property UserPermission[] $usersPermissions
 * @property UserGroup[]      $userGroups
 */
class User extends \yii\db\ActiveRecord
{
    use traits\SoftDeleteTrait;

    public static function tableName(): string
    {
        return 'users';
    }

    public function rules(): array
    {
        return [
            [['gsmverified', 'emailverified'], 'integer'],
            [['last_active', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['first_name', 'last_name', 'gsm'], 'required'],
            [['status', 'status_message'], 'string', 'max' => 255],
            [['first_name', 'last_name'], 'string', 'max' => 100],
            [['tcno'], 'string', 'max' => 11],
            [['gsm'], 'string', 'max' => 20],
            [['email'], 'required'],
            [['email'], 'email'],
            ['gmsverified', 'safe'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'status' => Yii::t('app', 'Status'),
            'status_message' => Yii::t('app', 'Status Message'),
            'active' => Yii::t('app', 'Active'),
            'last_active' => Yii::t('app', 'Last Active'),
            'tcno' => Yii::t('app', 'Tcno'),
            'gsm' => Yii::t('app', 'Gsm'),
            'gmsverified' => Yii::t('app', 'GSM verified'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
        ];
    }

    public function getAppointments(): \yii\db\ActiveQuery|AppointmentUserQuery
    {
        return $this->hasMany(Appointment::class, ['id' => 'appointment_id'])
            ->viaTable(AppointmentUser::tableName(), ['user_id' => 'id']);
    }

    public function getAuthidentities(): \yii\db\ActiveQuery|AuthidentityQuery
    {
        return $this->hasMany(Authidentity::class, ['user_id' => 'id'])->inverseOf('user');
    }

    public function getLogins(): \yii\db\ActiveQuery|LoginQuery
    {
        return $this->hasMany(Login::class, ['user_id' => 'id'])->inverseOf('user');
    }

    public function getBusinesses(): \yii\db\ActiveQuery|BusinessQuery
    {
        return $this->hasMany(Business::class, ['id' => 'business_id'])
            ->viaTable(UserBusiness::tableName(), ['user_id' => 'id']);
    }

    public function isInGroup($group): bool
    {
        return UserGroup::isUserInGroup($this->id, $group);
    }

    public function hasPermission($permission): bool
    {
        return UserPermission::hasPermission($this->id, $permission);
    }

    public static function find(): UserQuery
    {
        return new UserQuery(get_called_class());
    }

    public static function findforauth($condition): \yii\db\ActiveRecord|User|null
    {
        $user = User::find()->where($condition)->one();

        return $user;
    }

    public function getFullname(): string
    {
        return $this->first_name.' '.$this->last_name;
    }

}

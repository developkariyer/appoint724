<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string|null $status
 * @property string|null $status_message
 * @property int $gsmverified
 * @property int $emailverified
 * @property string|null $last_active
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 * @property string $first_name
 * @property string $last_name
 * @property string|null $tcno
 * @property string $gsm
 *
 * @property Appointment[] $appointments
 * @property Authidentity[] $authidentities
 * @property Login[] $logins
 * @property Business[] $Businesses
 * @property UserPermission[] $usersPermissions
 * @property UserGroup[] $userGroups
 */
class User extends \yii\db\ActiveRecord
{
    use traits\SoftDeleteTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'status' => Yii::t('app', 'Status'),
            'status_message' => Yii::t('app', 'Status Message'),
            'active' => Yii::t('app', 'Active'),
            'last_active' => Yii::t('app', 'Last Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'tcno' => Yii::t('app', 'Tcno'),
            'gsm' => Yii::t('app', 'Gsm'),
        ];
    }

    /**
     * Gets query for [[AppointmentsUsers]].
     *
     * @return \yii\db\ActiveQuery|AppointmentUserQuery
     */
    public function getAppointments()
    {
        return $this->hasMany(Appointment::class, ['id' => 'appointment_id'])
            ->viaTable(AppointmentUser::tableName(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Authidentities]].
     *
     * @return \yii\db\ActiveQuery|AuthidentityQuery
     */
    public function getAuthidentities()
    {
        return $this->hasMany(Authidentity::class, ['user_id' => 'id'])->inverseOf('user');
    }

    /**
     * Gets query for [[Logins]].
     *
     * @return \yii\db\ActiveQuery|LoginQuery
     */
    public function getLogins()
    {
        return $this->hasMany(Login::class, ['user_id' => 'id'])->inverseOf('user');
    }

    /**
     * Gets query for [[Businesses]].
     *
     * @return \yii\db\ActiveQuery|BusinessQuery
     */
    public function getBusinesses()
    {
        return $this->hasMany(Business::class, ['id' => 'business_id'])
            ->viaTable(UserBusiness::tableName(), ['user_id' => 'id']);
    }

    /**
     * Query for checking if User is in given group.
     *
     * @return bool
     */
    public function isInGroup($group)
    {
        return UserGroup::isUserInGroup($this->id, $group);
    }

    /**
     * Query for checking if User has permission to
     *
     * @return bool
     */
    public function hasPermission($permission)
    {
        return UserPermission::hasPermission($this->id, $permission);
    }

    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    public static function findforauth($condition)
    {
        $user = User::find()->where($condition)->one();

        // Put logic for checking if the user is allowed to login or not

        return $user;
    }
}

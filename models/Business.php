<?php

namespace app\models;

use DateTimeZone;
use Yii;

/**
 * @property int           $id
 * @property string        $name
 * @property string        $timezone
 * @property string|null   $created_at
 * @property string|null   $updated_at
 * @property string|null   $deleted_at
 * @property Appointment[] $appointments
 * @property resource[]    $resources
 * @property Rule[]        $rules
 * @property Service[]     $services
 * @property User[]        $usersBusinesses
 */
class Business extends \yii\db\ActiveRecord
{
    use traits\SoftDeleteTrait;

    public static function tableName(): string
    {
        return 'businesses';
    }

    public function rules(): array
    {
        return [
            [['name', 'timezone'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['timezone'], 'string', 'max' => 45],
            [['timezone'], 'validateTimezone'],
            [['name'], 'unique'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Business Name'),
            'timezone' => Yii::t('app', 'Timezone'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
        ];
    }

    public function validateTimezone($attribute, $params)
    {
        if (!in_array($this->$attribute, DateTimeZone::listIdentifiers())) {
            $this->addError($attribute, 'The timezone is invalid.');
        }
    }

    public function getAppointments(): \yii\db\ActiveQuery|AppointmentQuery
    {
        return $this->hasMany(Appointment::class, ['business_id' => 'id'])->inverseOf('business');
    }

    public function getResources(): \yii\db\ActiveQuery|ResourceQuery
    {
        return $this->hasMany(Resource::class, ['business_id' => 'id'])->inverseOf('business');
    }

    public function getRules(): \yii\db\ActiveQuery|RuleQuery
    {
        return $this->hasMany(Rule::class, ['business_id' => 'id'])->inverseOf('business');
    }

    public function getServices(): \yii\db\ActiveQuery|ServiceQuery
    {
        return $this->hasMany(Service::class, ['business_id' => 'id'])->inverseOf('business');
    }

    public function getUsers($role = null)
    {
        $query = $this->hasMany(User::class, ['id' => 'user_id'])
            ->viaTable(UserBusiness::tableName(), ['business_id' => 'id']);
    
        if ($role !== null) {
            $userIDs = UserBusiness::find()
                ->select('user_id')
                ->where(['role' => $role, 'business_id' => $this->id]);
            $query->andWhere(['id' => $userIDs]);
        }
    
        return $query;
    }
    
    public static function find(): BusinessQuery
    {
        return new BusinessQuery(get_called_class());
    }

    public function getPermissions(): \yii\db\ActiveQuery|PermissionQuery
    {
        return $this->hasMany(Permission::class, ['business_id' => 'id'])->inverseOf('business');
    }

}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "businesses".
 *
 * @property int $id
 * @property string $name
 * @property string $timezone
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Appointment[] $appointments
 * @property Resource[] $resources
 * @property Rule[] $rules
 * @property Service[] $services
 * @property User[] $usersBusinesses
 */
class Business extends \yii\db\ActiveRecord
{
    use traits\SoftDeleteTrait; 
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'businesses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['timezone'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'timezone' => Yii::t('app', 'Timezone'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
        ];
    }

    /**
     * Gets query for [[Appointments]].
     *
     * @return \yii\db\ActiveQuery|AppointmentQuery
     */
    public function getAppointments()
    {
        return $this->hasMany(Appointment::class, ['business_id' => 'id'])->inverseOf('business');
    }

    /**
     * Gets query for [[Resources]].
     *
     * @return \yii\db\ActiveQuery|ResourceQuery
     */
    public function getResources()
    {
        return $this->hasMany(Resource::class, ['business_id' => 'id'])->inverseOf('business');
    }

    /**
     * Gets query for [[Rules]].
     *
     * @return \yii\db\ActiveQuery|RuleQuery
     */
    public function getRules()
    {
        return $this->hasMany(Rule::class, ['business_id' => 'id'])->inverseOf('business');
    }

    /**
     * Gets query for [[Services]].
     *
     * @return \yii\db\ActiveQuery|ServiceQuery
     */
    public function getServices()
    {
        return $this->hasMany(Service::class, ['business_id' => 'id'])->inverseOf('business');
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])
            ->viaTable(UserBusiness::tableName(), ['business_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return BusinessQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BusinessQuery(get_called_class());
    }
}

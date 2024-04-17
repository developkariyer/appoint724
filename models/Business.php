<?php

namespace app\models;

use DateTimeZone;
use Yii;
use app\models\query\AppointmentQuery;
use app\models\query\BusinessQuery;
use app\models\query\PermissionQuery;
use app\models\query\ResourceQuery;
use app\models\query\RuleQuery;
use app\models\query\ServiceQuery;
use app\components\LogBehavior;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;


/**
 * @property int           $id
 * @property string        $name
 * @property string        $slug
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
class Business extends ActiveRecord
{
    use traits\SoftDeleteTrait;

    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $slug = Inflector::slug($this->name);
                // check if generated slug exists in Business table
                $count = Business::find()->where(['slug' => $slug])->count();
                if ($count > 0) {
                    $slug = $slug . '-ins' . $count;
                }
                $this->slug = $slug;
            }
            return true;
        }
        return false;
    }

    public function behaviors(): array
    {
        return [
            'logBehavior' => [
                'class' => LogBehavior::class,
                'eventTypeCreate' => LogBase::EVENT_BUSINESS_CREATED,
                'eventTypeUpdate' => LogBase::EVENT_BUSINESS_UPDATED,
                'eventTypeDelete' => LogBase::EVENT_BUSINESS_DELETED,
            ],
        ];
    }

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
            [['slug'], 'unique'],
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

    public function validateTimezone($attribute, $params): void
    {
        if (!in_array($this->$attribute, DateTimeZone::listIdentifiers())) {
            $this->addError($attribute, 'The timezone is invalid.');
        }
    }

    public function getAppointments(): ActiveQuery|AppointmentQuery
    {
        return $this->hasMany(Appointment::class, ['business_id' => 'id'])->inverseOf('business');
    }

    public function getResources(): ActiveQuery|ResourceQuery
    {
        return $this->hasMany(Resource::class, ['business_id' => 'id'])->inverseOf('business');
    }

    public function getRules(): ActiveQuery|RuleQuery
    {
        return $this->hasMany(Rule::class, ['business_id' => 'id'])->inverseOf('business');
    }

    public function getServices(): ActiveQuery|ServiceQuery
    {
        return $this->hasMany(Service::class, ['business_id' => 'id'])->inverseOf('business');
    }

    /**
     * @throws InvalidConfigException
     */
    public function getUsers($role = null): ActiveQuery
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

    public function getPermissions(): ActiveQuery|PermissionQuery
    {
        return $this->hasMany(Permission::class, ['business_id' => 'id'])->inverseOf('business');
    }

    public function getAvailableUsers($role)
    {
        $linkedUserIds = UserBusiness::find()
            ->select('user_id')
            ->where(['business_id' => $this->id, 'role' => $role]);

        return User::find()
            ->where(['not in', 'id', $linkedUserIds])
            ->orderBy(['first_name' => 'ASC']);
    }
}

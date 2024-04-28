<?php

namespace app\models;

use app\models\queries\UserQuery;
use app\models\views\UserBusinessRole;
use DateTimeZone;
use Yii;
use app\models\queries\AppointmentQuery;
use app\models\queries\BusinessQuery;
use app\components\LogBehavior;
use yii\base\InvalidConfigException;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveQuery;
use yii\helpers\Inflector;
use yii\db\ActiveRecord;


/**
 * @property int           $id
 * @property string        $name
 * @property string        $slug
 * @property string        $timezone
 * @property string|null   $expert_type_list
 * @property string|null   $resource_type_list
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

    protected function generateSlug($name): string
    {
        $slug = Inflector::slug($name);
        $count = Business::find()->where(['slug' => $slug])->count();
        if ($count > 0) {
            $slug = $slug . '-ins' . $count;
        }
        return $slug;
    }

    protected function fixExpertTypeList() : string
    {
        $currentTypes = json_decode($this->expert_type_list, true) ?: [];
        $usedTypes = $this->getExperts()
            ->select(['expert_type'])
            ->where(['not', ['expert_type' => null]])
            ->groupBy(['expert_type'])
            ->orderBy(['expert_type' => SORT_ASC])
            ->column();
        $updatedTypes = array_unique(array_merge($currentTypes, $usedTypes, ['']));
        sort($updatedTypes);
        return json_encode($updatedTypes);
    }

    protected function fixResourceTypeList(): string
    {
        $currentTypes = json_decode($this->resource_type_list, true) ?: [];
        $usedTypes = $this->getResources()
            ->select(['resource_type'])
            ->where(['not', ['resource_type' => null]])
            ->andWhere(['deleted_at'=> null])
            ->groupBy(['resource_type'])
            ->orderBy(['resource_type' => SORT_ASC])
            ->column();        
        $updatedTypes = array_unique(array_merge($currentTypes, $usedTypes, ['']));
        sort($updatedTypes);
        return json_encode($updatedTypes);
    }

    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($this->isNewRecord) {
            $this->slug = $this->generateSlug($this->name);
        }
        $this->expert_type_list = $this->fixExpertTypeList();        
        $this->resource_type_list = $this->fixResourceTypeList();
        return true;
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
            [['created_at', 'updated_at', 'deleted_at', 'expert_type_list', 'resource_type_list'], 'safe'],
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
            'name' => Yii::t('app', 'Business Name'),
            'timezone' => Yii::t('app', 'Timezone'),
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
        return $this->hasMany(Appointment::class, ['business_id' => 'id'])
            ->andWhere(['deleted_at' => null])
            ->inverseOf('business');
    }

    public function getResources(): ActiveQuery
    {
        return $this->hasMany(Resource::class, ['business_id' => 'id'])
            ->andWHere(['deleted_at' => null])
            ->inverseOf('business');
    }

    public function getRules(): ActiveQuery
    {
        return $this->hasMany(Rule::class, ['business_id' => 'id'])
            ->andWHere(['deleted_at' => null])
            ->inverseOf('business');
    }

    public function getServices(): ActiveQuery
    {
        return $this->hasMany(Service::class, ['business_id' => 'id'])
            ->andWHere(['deleted_at' => null])
            ->inverseOf('business');
}

    public function getUserBusinesses(): ActiveQuery
    {
        return $this->hasMany(UserBusiness::class, ['business_id' => 'id'])
            ->inverseOf('business');
    }

    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])
            ->viaTable(UserBusiness::tableName(), ['business_id' => 'id']);    
    }

    public function getUsersByRole($role)
    {
        if (!array_key_exists($role, Yii::$app->params['roles'])) {
            throw new InvalidConfigException('Invalid role');
        }

        $query = $this->getUsers();

        $userIDs = UserBusiness::find()
            ->select('user_id')
            ->where([
                'role' => $role, 
                'business_id' => $this->id
            ])
            ->asArray()->column();

        $query->andWhere(['id' => $userIDs]);
    
        return $query;
    }

    public function getUserBusinessRole($role, $active=true) 
    {
        return $this->hasMany(UserBusinessRole::class, ['business_id' => 'id'])
            ->where($active ? ['role' => $role, 'deleted_at' => null] : ['role' => $role])
            ->orderBy('fullname');
    }

    public function getExperts($active = true)
    {
        return $this->getUserBusinessRole('expert', $active);
    }

    public function getAdmins($active = true)
    {
        return $this->getUserBusinessRole('admin', $active);
    }

    public function getSecretaries($active = true)
    {
        return $this->getUserBusinessRole('secretary', $active);
    }

    public function getCustomers($active = true)
    {
        return $this->getUserBusinessRole('customer', $active);
    }

    public function getStaff($active = true)
    {
        return $this->getUserBusinessRole(['admin', 'secretary'], $active);
    }

    public static function find(): BusinessQuery
    {
        return new BusinessQuery(get_called_class());
    }

    public function getAvailableUsers()
    {
        $linkedUserIds = UserBusiness::find()
            ->select('user_id')
            ->where(['business_id' => $this->id])
            ->asArray()
            ->column();

        return User::find()
            ->where(['not in', 'id', $linkedUserIds])
            ->orderBy(['first_name' => 'ASC']);
    }

    public static function slugToId($slug)
    {
        $business = Business::find()->where(['slug' => $slug])->one();
        return $business ? $business->id : null;
    }

}

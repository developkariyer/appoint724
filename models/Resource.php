<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "resources".
 *
 * @property int $id
 * @property int $business_id
 * @property string|null $resource_type
 * @property string $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property AppointmentResource[] $appointmentsResources
 * @property Business $business
 */
class Resource extends \yii\db\ActiveRecord
{
    use traits\SoftDeleteTrait; 
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'resources';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['business_id'], 'required'],
            [['business_id'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['resource_type'], 'string', 'max' => 45],
            [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => Business::class, 'targetAttribute' => ['business_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'business_id' => Yii::t('app', 'Business ID'),
            'resource_type' => Yii::t('app', 'Resource Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
        ];
    }

    /**
     * Gets query for [[AppointmentsResources]].
     *
     * @return \yii\db\ActiveQuery|AppointmentResourceQuery
     */
    public function getAppointments()
    {
        return $this->hasMany(Appointment::class, ['id' => 'appointment_id'])
            ->viaTable(AppointmentResource::tableName(), ['resource_id' => 'id']);
    }

    /**
     * Gets query for [[Business]].
     *
     * @return \yii\db\ActiveQuery|BusinessQuery
     */
    public function getBusiness()
    {
        return $this->hasOne(Business::class, ['id' => 'business_id'])->inverseOf('resources');
    }

    /**
     * {@inheritdoc}
     * @return ResourceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ResourceQuery(get_called_class());
    }
}

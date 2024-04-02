<?php

namespace app\models;

/** @see Business */
class BusinessQuery extends \yii\db\ActiveQuery
{
    public function all($db = null): Business|array
    {
        return parent::all($db);
    }

    public function one($db = null): Business|array|null
    {
        return parent::one($db);
    }

    public function getDefaultBusiness()
    {
        // Filter the query to return the business with name 'Appointment SAAS'
        return $this->andWhere(['name' => 'Appointment SAAS']);
    }
}

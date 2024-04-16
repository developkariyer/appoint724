<?php

namespace app\models\query;

use yii\db\ActiveQuery;
use app\models\Business;

/** @see Business */
class BusinessQuery extends ActiveQuery
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

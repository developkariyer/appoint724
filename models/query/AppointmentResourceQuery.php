<?php

namespace app\models\query;

use yii\db\ActiveQuery;
use app\models\AppointmentResource;

/** @see AppointmentResource  */
class AppointmentResourceQuery extends ActiveQuery
{
    public function all($db = null): AppointmentResource|array
    {
        return parent::all($db);
    }

    public function one($db = null): AppointmentResource|array|null
    {
        return parent::one($db);
    }
}

<?php

namespace app\models;

use yii\db\ActiveQuery;

/** @see Appointment */
class AppointmentQuery extends ActiveQuery
{
    public function all($db = null): Appointment|array|null
    {
        return parent::all($db);
    }

    public function one($db = null): Appointment|array|null
    {
        return parent::one($db);
    }
}

<?php

namespace app\models;

/** @see Appointment */
class AppointmentQuery extends \yii\db\ActiveQuery
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

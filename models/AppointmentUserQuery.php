<?php

namespace app\models;

/** @see AppointmentUser */
class AppointmentUserQuery extends \yii\db\ActiveQuery
{

    public function all($db = null): AppointmentUser|array|null
    {
        return parent::all($db);
    }


    public function one($db = null): AppointmentUser|array|null
    {
        return parent::one($db);
    }
}

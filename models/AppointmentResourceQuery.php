<?php

namespace app\models;

/** @see AppointmentResource  */
class AppointmentResourceQuery extends \yii\db\ActiveQuery
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

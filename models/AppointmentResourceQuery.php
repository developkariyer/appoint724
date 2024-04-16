<?php

namespace app\models;

use yii\db\ActiveQuery;

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

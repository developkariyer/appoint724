<?php

namespace app\models\query;

use yii\db\ActiveQuery;
use app\models\AppointmentUser;

/** @see AppointmentUser */
class AppointmentUserQuery extends ActiveQuery
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

<?php

namespace app\models\queries;

use yii\db\ActiveQuery;
use app\models\Appointment;
use app\models\traits\SoftDeleteQueryTrait;

/** @see Appointment */
class AppointmentQuery extends ActiveQuery
{
    use SoftDeleteQueryTrait;
    
    public function all($db = null): Appointment|array|null
    {
        return parent::all($db);
    }

    public function one($db = null): Appointment|array|null
    {
        return parent::one($db);
    }
}

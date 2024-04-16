<?php

namespace app\models\query;

use yii\db\ActiveQuery;
use app\models\Service;

/** @see Service */
class ServiceQuery extends ActiveQuery
{
    public function all($db = null): Service|array
    {
        return parent::all($db);
    }

    public function one($db = null): Service|array|null
    {
        return parent::one($db);
    }
}

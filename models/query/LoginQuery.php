<?php

namespace app\models\query;

use yii\db\ActiveQuery;
use app\models\Login;

/** @see Login  */
class LoginQuery extends ActiveQuery
{
    public function all($db = null): Login|array
    {
        return parent::all($db);
    }

    public function one($db = null): Login|array|null
    {
        return parent::one($db);
    }
}

<?php

namespace app\models;

use yii\db\ActiveQuery;

/** @see UserBusiness */
class UserBusinessQuery extends ActiveQuery
{
    public function all($db = null): UserBusiness|array
    {
        return parent::all($db);
    }

    public function one($db = null): UserBusiness|array|null
    {
        return parent::one($db);
    }
}

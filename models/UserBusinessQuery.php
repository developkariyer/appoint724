<?php

namespace app\models;

/** @see UserBusiness */
class UserBusinessQuery extends \yii\db\ActiveQuery
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

<?php

namespace app\models;

/** @see Login  */
class LoginQuery extends \yii\db\ActiveQuery
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

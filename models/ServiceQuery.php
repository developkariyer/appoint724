<?php

namespace app\models;

/** @see Service */
class ServiceQuery extends \yii\db\ActiveQuery
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

<?php

namespace app\models;

/** @see Resource  */
class ResourceQuery extends \yii\db\ActiveQuery
{
    public function all($db = null): Resource|array
    {
        return parent::all($db);
    }

    public function one($db = null): Resource|array|null
    {
        return parent::one($db);
    }
}

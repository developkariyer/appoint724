<?php

namespace app\models;

/** @see Permission */
class PermissionQuery extends \yii\db\ActiveQuery
{
    public function all($db = null): Permission|array
    {
        return parent::all($db);
    }

    public function one($db = null): Permission|array|null
    {
        return parent::one($db);
    }
}

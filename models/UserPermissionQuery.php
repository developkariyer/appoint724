<?php

namespace app\models;

/** @see UserPermission */
class UserPermissionQuery extends \yii\db\ActiveQuery
{
    public function all($db = null): UserPermission|array
    {
        return parent::all($db);
    }

    public function one($db = null): UserPermission|array|null
    {
        return parent::one($db);
    }
}

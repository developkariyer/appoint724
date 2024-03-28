<?php

namespace app\models;

/** @see UserGroup */
class UserGroupQuery extends \yii\db\ActiveQuery
{
    public function all($db = null): UserGroup|array
    {
        return parent::all($db);
    }

    public function one($db = null): UserGroup|array|null
    {
        return parent::one($db);
    }
}

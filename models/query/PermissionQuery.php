<?php

namespace app\models\query;

use yii\db\ActiveQuery;
use app\models\Permission;

/** @see Permission */
class PermissionQuery extends ActiveQuery
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

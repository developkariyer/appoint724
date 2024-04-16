<?php

namespace app\models\query;

use yii\db\ActiveQuery;
use app\models\Authidentity;

/** @see Authidentity */
class AuthidentityQuery extends ActiveQuery
{
    public function all($db = null): Authidentity|array
    {
        return parent::all($db);
    }

    public function one($db = null): Authidentity|array|null
    {
        return parent::one($db);
    }
}

<?php

namespace app\models;

/** @see Authidentity */
class AuthidentityQuery extends \yii\db\ActiveQuery
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

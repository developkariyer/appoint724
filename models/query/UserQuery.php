<?php

namespace app\models\query;

use yii\db\ActiveQuery;
use app\models\User;

/** @see User */
class UserQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    public function all($db = null): User|array
    {
        return parent::all($db);
    }

    public function one($db = null): User|array|null
    {
        return parent::one($db);
    }
}

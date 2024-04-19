<?php

namespace app\models\queries;

use InvalidArgumentException;
use Yii;
use yii\db\ActiveQuery;
use app\models\User;
use app\models\traits\SoftDeleteQueryTrait;
use yii\db\Expression;

/** @see User */
class UserQuery extends ActiveQuery
{
    use SoftDeleteQueryTrait;
        
    public function all($db = null): User|array
    {
        return parent::all($db);
    }

    public function one($db = null): User|array|null
    {
        return parent::one($db);
    }

}

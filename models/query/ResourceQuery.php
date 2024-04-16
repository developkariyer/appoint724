<?php

namespace app\models\query;

use yii\db\ActiveQuery;
use app\models\Resource;

/** @see Resource  */
class ResourceQuery extends ActiveQuery
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

<?php

namespace app\models\query;

use yii\db\ActiveQuery;
use app\models\Rule;

/** @see Rule  */
class RuleQuery extends ActiveQuery
{
    public function all($db = null): Rule|array
    {
        return parent::all($db);
    }

    public function one($db = null): Rule|array|null
    {
        return parent::one($db);
    }
}

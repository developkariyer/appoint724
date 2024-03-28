<?php

namespace app\models;

/** @see Rule  */
class RuleQuery extends \yii\db\ActiveQuery
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

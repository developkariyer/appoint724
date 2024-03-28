<?php

namespace app\models;

/** @see Business */
class BusinessQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    public function all($db = null): Business|array
    {
        return parent::all($db);
    }

    public function one($db = null): Business|array|null
    {
        return parent::one($db);
    }
}

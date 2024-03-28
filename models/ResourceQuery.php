<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Resource]].
 *
 * @see Resource
 */
class ResourceQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Resource[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Resource|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

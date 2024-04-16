<?php

namespace app\models\query;

use yii\db\ActiveQuery;
use app\models\LogBase;

/**
 * This is the ActiveQuery class for [[LogBase]].
 *
 * @see LogBase
 */
class LogBaseQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return LogBase[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return LogBase|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

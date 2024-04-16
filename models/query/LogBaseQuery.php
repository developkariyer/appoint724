<?php

namespace app\models\query;

use yii\db\ActiveQuery;
use app\models\LogBase;
use yii\db\ActiveRecord;

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
    public function all($db = null): array
    {
        return parent::all($db);
    }

    public function one($db = null): array|ActiveRecord|null
    {
        return parent::one($db);
    }
}

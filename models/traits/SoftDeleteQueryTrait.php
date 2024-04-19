<?php

namespace app\models\traits;

use yii\db\ActiveQuery;

trait SoftDeleteQueryTrait
{
    public function active(): ActiveQuery
    {
        return $this->andWhere(['deleted_at' => null]);
    }

    public function deleted(): ActiveQuery
    {
        return $this->andWhere(['not', ['deleted_at' => null]]);
    }

}

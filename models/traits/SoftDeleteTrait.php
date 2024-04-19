<?php

namespace app\models\traits;

use yii\db\ActiveQuery;
use yii\db\Expression;

trait SoftDeleteTrait
{
    public function softDelete(): bool
    {
        if ($this->hasAttribute('deleted_at')) {
            $this->deleted_at = new Expression('NOW()');
            return $this->save(false, ['deleted_at']);
        }
        return false;
    }

    public function softUndelete(): bool
    {
        if ($this->hasAttribute('deleted_at')) {
            $this->deleted_at = null;
            return $this->save(false, ['deleted_at']);
        }
        return false;
    }

    public static function findActive($condition)
    {
        return static::find()->andWhere($condition)->andWhere(['deleted_at' => null]);
    }

    public static function findDeleted($condition)
    {
        return static::find()->andWhere($condition)->andWhere(['not', ['deleted_at' => null]]);
    }
}

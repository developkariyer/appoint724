<?php

namespace app\models\traits;

use yii\db\Expression;

trait SoftDeleteTrait
{

    public function delete() : bool
    {
        if ($this->hasAttribute('deleted_at')) {
            return $this->softDelete();
        } else {
            return $this->hardDelete();
        }
    }

    public function hardDelete() : bool
    {
        return parent::delete();
    }

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

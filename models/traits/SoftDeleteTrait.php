<?php

namespace app\models\traits;

use yii\db\ActiveQuery;
use yii\db\Expression;

trait SoftDeleteTrait
{
    /**
     * Softly deletes a record by setting its `deleted_at` attribute to the current timestamp.
     */
    public function softDelete(): bool
    {
        if ($this->hasAttribute('deleted_at')) {
            $this->deleted_at = new Expression('NOW()');
            return $this->save(false, ['deleted_at']);
        }

        return false;
    }

    /**
     * Modifies the ActiveQuery used by the model to exclude soft-deleted records by default.
     */
    public static function find(): ActiveQuery
    {
        return parent::find()->andWhere(['deleted_at' => null]);
    }

    /**
     * Adds a method to the ActiveQuery for including soft-deleted records in the results.
     */
    public static function findIncludingDeleted(): ActiveQuery
    {
        return parent::find();
    }

    /**
     * Adds a method to the ActiveQuery for exclusively querying soft-deleted records.
     */
    public static function findOnlyDeleted(): ActiveQuery
    {
        return parent::find()->andWhere(['not', ['deleted_at' => null]]);
    }
}

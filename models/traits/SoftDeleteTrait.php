<?php

namespace app\models\traits;

trait SoftDeleteTrait
{
    /**
     * Softly deletes a record by setting its `deleted_at` attribute to the current timestamp.
     */
    public function softDelete()
    {
        if ($this->hasAttribute('deleted_at')) {
            $this->deleted_at = new \yii\db\Expression('NOW()');
            return $this->save(false, ['deleted_at']);
        }

        return false;
    }

    /**
     * Modifies the ActiveQuery used by the model to exclude soft-deleted records by default.
     */
    public static function find()
    {
        return parent::find()->andWhere(['deleted_at' => null]);
    }

    /**
     * Adds a method to the ActiveQuery for including soft-deleted records in the results.
     */
    public static function findIncludingDeleted()
    {
        return parent::find();
    }

    /**
     * Adds a method to the ActiveQuery for exclusively querying soft-deleted records.
     */
    public static function findOnlyDeleted()
    {
        return parent::find()->andWhere(['not', ['deleted_at' => null]]);
    }
}

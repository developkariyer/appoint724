<?php

namespace app\models\traits;

use yii;

trait BusinessCacheTrait
{
    private function deleteCache(): void
    {
        $cacheKey = 'business_'.$this->business_id.'_stats';
        Yii::$app->cache->delete($cacheKey);
    }

    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);
        $this->deleteCache();
    }

    public function afterDelete(): void
    {
        parent::afterDelete();
        $this->deleteCache();
    }


}

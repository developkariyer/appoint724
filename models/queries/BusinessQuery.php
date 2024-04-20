<?php

namespace app\models\queries;

use app\models\UserBusiness;
use InvalidArgumentException;
use Yii;
use yii\db\ActiveQuery;
use app\models\Business;
use app\models\traits\SoftDeleteQueryTrait;
use yii\db\Expression;

/** @see Business */
class BusinessQuery extends ActiveQuery
{
    use SoftDeleteQueryTrait;
    
    public function all($db = null): Business|array
    {
        return parent::all($db);
    }

    public function one($db = null): Business|array|null
    {
        return parent::one($db);
    }

    public function byUserRoles($userId, $roles): BusinessQuery
    {
        $userBusinessTable = UserBusiness::tableName();
        $businessTable = Business::tableName();

        $this->innerJoin("$userBusinessTable AS ub", "{$businessTable}.id = ub.business_id")
            ->andWhere(['ub.user_id' => $userId])
            ->andWhere(['ub.role' => $roles])
            ->andWhere(["{$businessTable}.deleted_at" => null]);

        return $this;
    }

}

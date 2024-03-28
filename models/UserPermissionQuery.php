<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserPermission]].
 *
 * @see UserPermission
 */
class UserPermissionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return UserPermission[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return UserPermission|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

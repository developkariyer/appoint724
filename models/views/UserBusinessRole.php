<?php

namespace app\models\views;

use app\models\User;
use yii\db\ActiveRecord;


/**
 * @property int              $id
 * @property int              $business_id
 * @property string           $role
 * @property string|null      $status
 * @property string|null      $status_message
 * @property int              $gsmverified
 * @property int              $emailverified
 * @property int              $tcnoverified
 * @property string|null      $last_active
 * @property string|null      $created_at
 * @property string|null      $updated_at
 * @property string|null      $deleted_at
 * @property string           $first_name
 * @property string           $last_name
 * @property string|null      $tcno
 * @property string           $gsm
 * @property string           $language
 * @property string           $fullname
 * @property int              $dogum_yili
 * @property int              $remainingBusinessCount
 */
class UserBusinessRole extends ActiveRecord
{

    public static function tableName(): string
    {
        return 'vw_users_businesses';
    }

    public static function primaryKey()
    {
        return ['id', 'business_id'];  // Replace with the actual column names that together are unique
    }
    
    public function attributeLabels(): array
    {
        return (new User())->attributeLabels();
    }

    public function getMyGsm(): string
    {
        return preg_replace("/^(\d{3})(\d{3})(\d{4})$/", "($1) $2 $3", $this->gsm);
    }

}

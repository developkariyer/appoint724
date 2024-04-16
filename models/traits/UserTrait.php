<?php

namespace app\models\traits;

use Yii;

trait UserTrait
{
    
    public function commonRules(): array
    {
        return [
            [['gsmverified', 'emailverified', 'tcnoverified'], 'boolean'],
            [['status', 'status_message'], 'string', 'max' => 255],
            [['first_name', 'last_name'], 'string', 'max' => 100],
            [['tcno'], 'string', 'max' => 11, 'min' => 11],
//            [['gsm'], 'string', 'max' => 10, 'min' => 10],
            [['email'], 'email'],        
            [['email'], 'unique', 'targetClass' => '\app\models\User', 'message' => 'This email has already been taken.', 
                        'filter' => function ($query) { if (!Yii::$app->user->isGuest && Yii::$app->user->identity->user_id) { $query->andWhere(['<>', 'id', Yii::$app->user->identity->user_id]); } }],
            [['gsm'], 'unique', 'targetClass' => '\app\models\User', 'message' => 'This GSM number has already been taken.', 
                        'filter' => function ($query) { if (!Yii::$app->user->isGuest && Yii::$app->user->identity->user_id) { $query->andWhere(['<>', 'id', Yii::$app->user->identity->user_id]); } }],

        ];
    }

}

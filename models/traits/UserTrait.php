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
            [['tcno'], 'string', 'max' => 11],
            [['email'], 'email'],        
            [['email'], 'unique', 'targetClass' => '\app\models\User', 'message' => 'This email has already been taken.', 
                        'filter' => function ($query) {
                            $id = !Yii::$app->user->isGuest ? Yii::$app->request->get('id', Yii::$app->user->identity->user->id) : null;
                            if ($id) {
                                $query->andWhere(['<>', 'id', $id]);
                            }
                            $query->andWhere(['deleted_at' => null]); 
                        }],
            [['gsm'], 'unique', 'targetClass' => '\app\models\User', 'message' => 'This GSM number has already been taken.', 
                        'filter' => function ($query) {
                            $id = !Yii::$app->user->isGuest ? Yii::$app->request->get('id', Yii::$app->user->identity->user->id) : null;
                            if ($id) {
                                $query->andWhere(['<>', 'id', $id]);
                            }
                            $query->andWhere(['deleted_at' => null]); 
                        }],
        ];
    }

}

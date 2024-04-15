<?php 

namespace app\components;

use Yii;
use yii\base\Component;
use yii\helpers\Html;
use app\components\MyUrl;

class MyMenu extends Component
{

    public static function getSuperAdminNavItems()
    {
        return [
            [
                'label' => Yii::t('app', 'Businesses'),
                'items' => [
                    [
                        'label' => Yii::t('app', 'List Businesses'),
                        'url' => MyUrl::to(['business/index']),
                    ],
                    [
                        'label' => Yii::t('app', 'Create Business'),
                        'url' => MyUrl::to(['business/create']),
                    ],
                ],
            ],
            [
                'label' => Yii::t('app', 'Users'),
                'items' => [
                    [
                        'label' => Yii::t('app', 'List Users'),
                        'url' => MyUrl::to(['user/index']),
                    ],
                    [
                        'label' => Yii::t('app', 'Add New User'),
                        'url' => MyUrl::to(['user/create']),
                    ],
                    [
                        'label' => Yii::t('app', 'Reset User Password'),
                        'url' => MyUrl::to(['user/reset']),
                    ],

                ],
            ],
        ];
    }

    public static function getSuperAdminMenuItems()
    {
        $businesses = \app\models\Business::find()->orderBy('name')->all();
        $items = [];
        foreach ($businesses as $business) {
            if ($business->id == Yii::$app->request->get('id')) $isExpanded = true; else $isExpanded = false;
            $items[] = [
                'label' => $business->name,
                'content' => [
                    [
                        'label' => Yii::t('app', 'Business Details'),
                        'url' => MyUrl::to(['business/view/'.$business->id])
                    ],
                    [
                        'label' => Yii::t('app', 'Appointments'),
                        'url' => MyUrl::to(['appointment/business/'.$business->id])
                    ],
                    [
                        'label' => Yii::t('app','Admins'),
                        'url' => MyUrl::to(['user/business/'.$business->id])
                    ],
                    [
                        'label' => Yii::t('app', 'Resources'),
                        'url' => MyUrl::to(['resource/business/'.$business->id])
                    ],
                ],
                'bodyOptions' => ['class' => 'p-0'],
                'options' => ['class' => 'p-0'],
                'raw' => true,
                'isExpanded' => $isExpanded,
            ];
        }
        return $items;
    }

}

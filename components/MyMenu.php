<?php 

namespace app\components;

use app\models\Business;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Url;

class MyMenu extends Component
{

    public static function getLogNavItems(): array
    {
        return Yii::$app->user->isGuest ? [
            'label' => Yii::t('app', 'Guest'),
            'options' => ['class' => 'dropdown nav-item'],
            'items' => [
                [
                    'label' => '<i class="bi bi-box-arrow-in-right"></i> '.Yii::t('app', 'Login with Password'),
                    'url' => MyUrl::to(['site/login/password']),
                ],
                [
                    'label' => '<i class="bi bi-box-arrow-in-right"></i> '.Yii::t('app', 'Login with SMS'),
                    'url' => MyUrl::to(['site/login/sms_request']),
                ],
                [
                    'label' => '<i class="bi bi-box-arrow-in-right"></i> '.Yii::t('app', 'Login with Link'),
                    'url' => MyUrl::to(['site/login/link']),
                ],
                [
                    'label' => '<i class="bi bi-box-arrow-in-right"></i> '.Yii::t('app', 'Other'),
                    'url' => MyUrl::to(['site/login/other']),
                ],
            ],
            'dropdownOptions' => ['class' => 'dropdown-menu dropdown-menu-end'],
        ] : [
            'label' => '<i class="bi bi-person"></i> '.Yii::$app->user->identity->username,
            'options' => ['class' => 'dropdown nav-item'],
            'items' => [
                [
                    'label' => '<i class="bi bi-key"></i> '.Yii::t('app', 'Change Password'),
                    'url' => MyUrl::to(['user/password']),
                ],
                [
                    'label' => '<i class="bi bi-person-vcard"></i> '.Yii::t('app', 'User Information'),
                    'url' => MyUrl::to(['user/update']),
                ],
                [
                    'label' => '',
                ],
                Html::beginForm(MyUrl::to(['site/logout'])).
                Html::submitButton(
                    ' <i class="bi bi-box-arrow-right"></i> '.Yii::t('app', 'Logout'),
                    ['class' => 'btn ']
                ).Html::endForm(),
            ],
            'dropdownOptions' => ['class' => 'dropdown-menu dropdown-menu-end'],
        ];
    }

    /**
     * @throws InvalidConfigException
     */
    public static function getLangNavItems(): array
    {
        $supportedLanguages = Yii::$app->params['supportedLanguages'];
        $pathInfo = Yii::$app->request->getPathInfo();
        $segments = explode('/', $pathInfo);
        if (in_array($segments[0], array_keys($supportedLanguages))) unset($segments[0]);
        $languageItems = [];
        foreach ($supportedLanguages as $lang=>$alt) {
            $languageItems[$lang] = [
                'label' => Html::img(Url::to("@web/images/flags/$lang.png"), [
                    'class' => 'langFlags',
                    'alt' => $alt,
                ]),
                'url' => ["/$lang/".implode('/', $segments)],
                'encode' => false,
            ];
        }
        
        return [
            'options' => ['class' => 'lang-nav-bar'],
            'label' => $languageItems[Yii::$app->language]['label'],
            'encode' => false,
            'items' => $languageItems,
        ];
    }

    public static function getNavItems(): array
    {
        if (Yii::$app->user->isGuest) return [
            [
                'label' => Yii::t('app', 'Login'),
                'url' => MyUrl::to(['/site/login']),
            ],
            [
                'label' => Yii::t('app', 'New User'),
                'url' => MyUrl::to(['/user/register']),
            ],
        ];

        if (Yii::$app->user->identity->user->superadmin) return [
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
        return [];
    }

    public static function getLeftMenuItems(): ?array
    {
        if (Yii::$app->user->isGuest) return [];

        if (Yii::$app->user->identity->user->superadmin) {
            $businesses = Business::find()->orderBy('name')->all();
            $items = [];
            foreach ($businesses as $business) {
                if ($business->slug == Yii::$app->request->get('slug')) $isExpanded = true; else $isExpanded = false;
                $items[] = [
                    'label' => $business->name,
                    'content' => [
                        [
                            'label' => Yii::t('app', 'Business Settings'), 
                            'url' => MyUrl::to(['business/update/'.$business->slug])
                        ],
                        [
                            'label' => Yii::t('app', 'Appointments').' <span class="badge text-black bg-success-subtle">'.$business->getAppointments()->count().'</span>',
                            'url' => MyUrl::to(['appointment/business/'.$business->slug])
                        ],
                        [
                            'label' => Yii::t('app','Admins').' <span class="badge text-black bg-success-subtle">'.$business->getUsers('admin')->count().'</span>',
                            'url' => MyUrl::to(['business/user/admin/'.$business->slug])
                        ],
                        [
                            'label' => Yii::t('app','Secretaries').' <span class="badge text-black bg-success-subtle">'.$business->getUsers('secretary')->count().'</span>',
                            'url' => MyUrl::to(['business/user/secretary/'.$business->slug])
                        ],
                        [
                            'label' => Yii::t('app','Experts').' <span class="badge text-black bg-success-subtle">'.$business->getUsers('expert')->count().'</span>',
                            'url' => MyUrl::to(['business/user/expert/'.$business->slug])
                        ],
                        [
                            'label' => Yii::t('app','Customers').' <span class="badge text-black bg-success-subtle">'.$business->getUsers('customer')->count().'</span>',
                            'url' => MyUrl::to(['business/user/customer/'.$business->slug])
                        ],
                        [
                            'label' => Yii::t('app', 'Resources').' <span class="badge text-black bg-success-subtle">'.$business->getResources()->count().'</span>',
                            'url' => MyUrl::to(['resource/business/'.$business->slug])
                        ],
                        [
                            'label' => Yii::t('app', 'Rules').' <span class="badge text-black bg-success-subtle">'.$business->getRules()->count().'</span>',
                            'url' => MyUrl::to(['rule/business/'.$business->slug])
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
        return null;
    }

}

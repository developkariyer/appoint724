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
        return [];
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

    public static function getLeftMenuItems(): array
    {
        if (Yii::$app->user->identity->user->superadmin) {
            $businesses = Business::find()->orderBy('name')->all();
            $items = [];
            foreach ($businesses as $business) {
                $isExpanded = ($business->slug === Yii::$app->request->get('slug')) ? true : false;
                if ($isExpanded && Yii::$app->controller->action->id) {
                    if (Yii::$app->controller->action->id === 'user') {
                        $highlightedAction = Yii::$app->request->get('role');
                    } else {
                        $highlightedAction = Yii::$app->controller->action->id;
                    }
                } else {
                    $highlightedAction = '';
                }

                // Cache business stats
                $cacheKey = 'business_'.$business->id.'_stats';
                $businessStats = Yii::$app->cache->get($cacheKey);
                if ($businessStats === false) {
                    $businessStats = [
                        'appointments' => $business->getAppointments()->count(),
                        'resources' => $business->getResources()->count(),
                        'rules' => $business->getRules()->count(),
                        'services' => $business->getServices()->count(),
                        'users' => [],
                    ];
                    foreach (Yii::$app->params['roles'] as $key=>$value) {
                        $businessStats['users'][$key] = $business->getUsers($key)->count();
                    }
                    Yii::$app->cache->set($cacheKey, $businessStats, 18400);
                }

                $contents = [
                    [
                        'label' => Yii::t('app', 'Business Settings'), 
                        'url' => MyUrl::to(['business/update/'.$business->slug]),
                        'class' => $highlightedAction === 'update' ? 'list-group-item-info' : '',
                    ],
                    [
                        'label' => Yii::t('app', 'Appointments').' <span class="badge text-black bg-success-subtle">'.$businessStats['appointments'].'</span>',
                        'url' => MyUrl::to(['business/appointment/'.$business->slug]),
                        'class' => $highlightedAction === 'appointment' ? 'list-group-item-info' : '',
                    ],
                ];

                foreach (Yii::$app->params['roles'] as $key=>$value) {
                    $contents[] = [
                        'label' => Yii::t('app', $value).' <span class="badge text-black bg-success-subtle">'.$businessStats['users'][$key].'</span>',
                        'url' => MyUrl::to(["business/user/$key/$business->slug"]),
                        'class' => $highlightedAction === $key ? 'list-group-item-info' : '',
                    ];
                }

                $contents[] = [
                    'label' => Yii::t('app', 'Resources').' <span class="badge text-black bg-success-subtle">'.$businessStats['resources'].'</span>',
                    'url' => MyUrl::to(['business/resource/'.$business->slug]),
                    'class' => $highlightedAction === 'resource' ? 'list-group-item-info' : '',
                ];
                $contents[] = [
                    'label' => Yii::t('app', 'Rules').' <span class="badge text-black bg-success-subtle">'.$businessStats['rules'].'</span>',
                    'url' => MyUrl::to(['business/rule/'.$business->slug]),
                    'class' => $highlightedAction === 'rule' ? 'list-group-item-info' : '',
                ];
                $contents[] = [
                    'label' => Yii::t('app', 'Services').' <span class="badge text-black bg-success-subtle">'.$businessStats['services'].'</span>',
                    'url' => MyUrl::to(['business/service/'.$business->slug]),
                    'class' => $highlightedAction === 'service' ? 'list-group-item-info' : '',
                ];

                $items[] = [
                    'label' => $business->name,
                    'content' => $contents,
                    'bodyOptions' => ['class' => 'p-0'],
                    'options' => ['class' => 'p-0'],
                    'raw' => true,
                    'isExpanded' => $isExpanded,
                ];
            }
            return $items;
        }
        return [];
    }

}

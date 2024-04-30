<?php 

namespace app\components;

use app\models\Business;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;

class MyMenu extends Component
{
    private $businesses = [];
    private $isGuest = true;
    private $business = null;
    private $slug = null;

    public function init()
    {
        parent::init();
        $this->isGuest = Yii::$app->user->isGuest;
        if ($this->isGuest) {
            $this->businesses = [];
            return;
        } 
        $this->businesses = Yii::$app->user->identity->user->superadmin ?
            Business::find()->active()->orderBy('name')->all() :
            Business::find()->byUserRoles(Yii::$app->user->identity->user->id, ['admin', 'secretary'])->orderBy('name')->all();
        if (Yii::$app->request->get('slug', false) === false) {
            if (!Yii::$app->session->has('slug')) {return;}
            $this->slug = Yii::$app->session->get('slug');
        } else {
            $this->slug = Yii::$app->request->get('slug');
        }
        if ($this->business = Business::find()->where(['slug' => $this->slug])->one()) {
            Yii::$app->session->set('slug', $this->business->slug);
        }
    }

    public function getLogNavItems(): array
    {
        return $this->isGuest ? [
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
                    'label' => '<i class="bi bi-house"></i> '.Yii::t('app', 'Home Page'),
                    'url' => Yii::$app->homeUrl,
                ],
                [
                    'label' => '',
                ],
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

    public  function getAuthorizedBusinessMenu() :array|null
    {
        if ($this->isGuest) return [];
        $items = [];
        foreach($this->businesses as $business) {
            $items[] = [
                'label' => $business->name,
                'url' => MyUrl::to(['appointment/view/'.$business->slug]),
                'options' => ['class' => 'nav-item'],
            ];
        }
        $items[] = ['label' => ''];
        if (Yii::$app->user->identity->user->superadmin || Yii::$app->user->identity->user->remainingBusinessCount) {
            $items[] = [
                'label' => Yii::t('app', 'Create Business'),
                'url' => MyUrl::to(['business/create']),
                'options' => ['class' => 'nav-item'],
            ];
        } else {
            $items[] = [
                'label' => Yii::t('app', 'Buy Business Slot'),
                'url' => MyUrl::to(['business/slot']),
                'options' => ['class' => 'nav-item'],
            ];
        }
        return [[
            'label' => $this->business ? $this->business->name : Yii::t('app', 'Businesses'),
            'items' => $items,
            'encodeLabels' => false,        
        ]];
    }

    private function getNavItemsBusinessSettings() : array
    {
        return [
            'label' => Yii::t('app', 'Business Settings'),
            'url' => MyUrl::to(['business/update/'.$this->business->slug]),
            'items' => [
                [
                    'label' => Yii::t('app', 'Business Name'),
                    'url' => MyUrl::to(['business/update/'.$this->business->slug]),
                ],
                [
                    'label' => Yii::t('app', 'Timezone'),
                    'url' => MyUrl::to(['business/update/'.$this->business->slug]),
                ],
                [
                    'label' => Yii::t('app', 'Expert Type List'),
                    'url' => MyUrl::to(['business/update/'.$this->business->slug]),
                ],
                [
                    'label' => Yii::t('app', 'Resource Type List'),
                    'url' => MyUrl::to(['business/update/'.$this->business->slug]),
                ],
                [
                    'label' => '',
                ],
                [
                    'label' => Yii::t('app', 'Admins'),
                    'url' => MyUrl::to(["business/user/{$this->business->slug}/admin"]),
                ],
                [
                    'label' => Yii::t('app', 'Secretaries'),
                    'url' => MyUrl::to(["business/user/{$this->business->slug}/secretary"]),
                ],
            ],
            
        ];
    }

    private function getNavItemsAppointments(): array
    {
        $services = $this->business->getServices()->all();
        $serviceItems = [];
        foreach($services as $service) {
            $serviceItems[] = [
                'label' => $service->name,
                'url' => MyUrl::to(['appointment/view/'.$this->business->slug]),
            ];
        }
        return [
            'label' => Yii::t('app', 'Appointments'),
            'items' =>[
                [
                    'label' => Yii::t('app', 'Day View'),
                    'url' => MyUrl::to(['appointment/view/'.$this->business->slug]),        
                ],
                [
                    'label' => Yii::t('app', 'Week View'),
                    'url' => MyUrl::to(['appointment/view/'.$this->business->slug]),        
                ],
                [
                    'label' => Yii::t('app', 'Month View'),
                    'url' => MyUrl::to(['appointment/view/'.$this->business->slug]),        
                ],
                [
                    'label' => Yii::t('app', 'Listing View'),
                    'url' => MyUrl::to(['appointment/view/'.$this->business->slug]),        
                ],
                [
                    'label' => '',
                ],
                [
                    'label' => Yii::t('app', 'New Appointment'),
                    'url' => MyUrl::to(['appointment/view/'.$this->business->slug]),        
                ],
                ...$serviceItems,
            ],
        ];
    }

    private function getNavItemsExperts(): array
    {
        $expertItems = [];
        $experts = $this->business->getUserBUsinessRole('expert')->all();
        foreach ($experts as $expert) {
            $expertItems[] = [
                'label' => "{$expert->fullname}". ($expert->expert_type ? " ({$expert->expert_type})" : ""),
                'url' => MyUrl::to(["user/update/{$expert->id}"]),
            ];
        }
        $expertItems[] = [
            'label' => '',
        ];
        $expertItems[] = [
            'label' => Yii::t('app', 'Add New'),
            'url' => MyUrl::to(["user/add/{$this->business->slug}/expert"]),
        ];
        $expertItems[] = [
            'label' => Yii::t('app', 'Show All'),
            'url' => MyUrl::to(["business/user/{$this->business->slug}/expert"]),
        ];
        return [
            'label' => Yii::t('app', 'Experts'),
            'items' => $expertItems,
        ];
    }

    private function getNavITemsRelations($relationKey, $relationName): array
    {
        $relationItems = [];
        $relations = $this->getRelationData($this->business, $relationKey)->all();
        foreach ($relations as $relation) {
            $relationItems[] = [
                'label' => $relation->name,
                'url' => MyUrl::to(["business/$relationKey/{$this->business->slug}/{$relation->id}"]),
            ];
        }
        $relationItems[] = [
            'label' => '',
        ];
        $relationItems[] = [
            'label' => Yii::t('app', 'Add New'),
            'url' => MyUrl::to(["business/$relationKey/{$this->business->slug}"]),
        ];
        $relationItems[] = [
            'label' => Yii::t('app', 'Show All'),
            'url' => MyUrl::to(["business/$relationKey/{$this->business->slug}"]),
        ];
        return [
            'label' => $relationName,
            'items' => $relationItems,
        ];
    }

    public function getNavItems(): array
    {
        if ($this->isGuest) return [];
        if (!$this->business) return [];
        $items = [];
        $items[] = $this->getNavItemsBusinessSettings();
        $items[] = $this->getNavItemsAppointments();
        $items[] = $this->getNavItemsExperts();

        $dummyArray = ['resource'=>Yii::t('app', 'Resources'), 'service'=>Yii::t('app', 'Services'), 'rule'=>Yii::t('app', 'Rules')];
        foreach ($dummyArray as $relationKey => $relationName) {
            $items[] = $this->getNavItemsRelations($relationKey, $relationName);
        }
        return $items;
    }

    private function getRelationData($model, $relation) {
        $getter = 'get' . ucfirst($relation) . 's';
        if (method_exists($model, $getter)) {
            return $model->$getter()->andWhere(['deleted_at' => null]);
        } else {
            throw new BadRequestHttpException("Invalid relation method: {$getter}");
        }
    }

    public static function getLeftMenuItems(): array
    {
        if (Yii::$app->user->isGuest) return [];
        return [];

        $businesses = Yii::$app->user->identity->user->superadmin ?
            Business::find()->active()->orderBy('name')->all() :
            Business::find()->byUserRoles(Yii::$app->user->identity->user->id, ['admin', 'secretary'])->orderBy('name')->all();

        $businessCount = count($businesses);

        $items = [];
        foreach ($businesses as $business) {
            $isExpanded = ($businessCount == 1) || ($business->slug === Yii::$app->request->get('slug')) ? true : false;
            if ($isExpanded && Yii::$app->controller->action->id) {
                if (Yii::$app->controller->action->id === 'user') {
                    $highlightedAction = Yii::$app->request->get('role');
                } else {
                    $highlightedAction = Yii::$app->controller->action->id;
                }
            } else {
                $highlightedAction = '';
            }

            $cacheKey = 'business_'.$business->id.'_stats';
            $businessStats = Yii::$app->cache->get($cacheKey);
            if ($businessStats === false) {
                $businessStats = [
                    'appointments' => $business->getAppointments()->active()->count(),
                    'resources' => $business->getResources()->andWhere(['deleted_at' => null])->count(),
                    'rules' => $business->getRules()->andWhere(['deleted_at' => null])->count(),
                    'services' => $business->getServices()->andWhere(['deleted_at' => null])->count(),
                    'users' => [],
                ];
                foreach (Yii::$app->params['roles'] as $key=>$value) {
                    $businessStats['users'][$key] = $business->getUsersByRole($key)->count();
                }
                Yii::$app->cache->set($cacheKey, $businessStats, 86400);
            }

            $contents = [
                [
                    'label' => Yii::t('app', 'Business Settings'), 
                    'url' => MyUrl::to(['business/update/'.$business->slug]),
                    'class' => $highlightedAction === 'update' ? 'list-group-item-info' : '',
                ],
                [
                    'label' => Yii::t('app', 'Appointments').' <span class="badge text-black bg-success-subtle">'.$businessStats['appointments'].'</span>',
                    'url' => MyUrl::to(['appointment/view/'.$business->slug]),
                    'class' => $highlightedAction === 'appointment' ? 'list-group-item-info' : '',
                ],
            ];

            foreach (Yii::$app->params['roles'] as $key=>$value) {
                $contents[] = [
                    'label' => Yii::t('app', $value).' <span class="badge text-black bg-success-subtle">'.$businessStats['users'][$key].'</span>',
                    'url' => MyUrl::to(["business/user/$business->slug/$key"]),
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

}

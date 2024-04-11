<?php

namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Nav;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\MyUrl;

class Navigation extends Widget
{
    public $supportedLanguages;
    public $currentPath;
    private $userType;

    const userGuest = 'guest';
    const userSuperAdmin = 'superadmin';
    const userCustomer = 'customer';
    const userExpert = 'expert';
    const userAdmin = 'admin';
    const userSecretary = 'secretary';
    const classNavBar =  [
        self::userGuest => 'navbar-expand-md navbar-dark bg-dark fixed-top',
        self::userSuperAdmin => 'navbar-expand-md navbar-dark bg-danger fixed-top',
        self::userCustomer => 'navbar-expand-md navbar-dark bg-primary fixed-top',
        self::userExpert => 'navbar-expand-md navbar-dark bg-primary fixed-top',
        self::userSecretary => 'navbar-expand-md navbar-dark bg-primary fixed-top',
        self::userAdmin => 'navbar-expand-md navbar-dark bg-primary fixed-top',
    ];


    public function run()
    {

        if (Yii::$app->user->isGuest) {
            $this->userType = self::userGuest;
        } else {
            if (Yii::$app->user->identity->user->superadmin) {
                $this->userType = self::userSuperAdmin;
            } else {
                // Business logic will come here later
                $this->userType = self::userCustomer;
            }
        }

        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => ['class' => self::classNavBar[$this->userType]],
        ]);

        // build language selector dropdown
        $pathInfo = $this->currentPath;
        $segments = explode('/', $pathInfo);
        if (in_array($segments[0], array_keys($this->supportedLanguages))) unset($segments[0]);
        $languageItems = [];
        foreach ($this->supportedLanguages as $lang=>$alt) {
            $languageItems[$lang] = [
                'label' => Html::img(Url::to("@web/images/flags/{$lang}.png"), [
                    'class' => 'langflags',
                    'alt' => $alt,
                ]),
                'url' => ["/{$lang}/".implode('/', $segments)],
                'encode' => false,
            ];
        }

        // login/logout items and dropdown
        $lognav = Yii::$app->user->isGuest ? ['label' => Yii::t('app', 'Login'), 'url' => MyUrl::to(['site/login'])] : [
            'label' => '<i class="bi bi-person"></i> '.Yii::$app->user->identity->username,
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
                Html::beginForm(MyUrl::to(['site/logout']), 'post').
                Html::submitButton(
                    ' <i class="bi bi-box-arrow-right"></i> '.Yii::t('app', 'Logout'),
                    ['class' => 'btn ']
                ).Html::endForm(),
            ]
        ];

        // superadmin menu
        if ($this->userType == self::userSuperAdmin) {
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav'],
                'items' => [
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
                ],
                'encodeLabels' => false, 
            ]);
        }

        echo "<div class='ms-auto'>";
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav'],
            'items' => [
                [
                    'options' => ['class' => 'lang-nav-bar'],
                    'label' => $languageItems[Yii::$app->language]['label'],
                    'encode' => false,
                    'items' => $languageItems,
                ],
                $lognav,
            ],
            'encodeLabels' => false, 
        ]);
        echo "</div>";

        NavBar::end();
    }
}

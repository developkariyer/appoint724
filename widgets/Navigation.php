<?php

namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Nav;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\MyUrl;
use app\components\MyMenu;

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
        self::userGuest => 'navbar-expand-md navbar-dark bg-dark fixed-top p-0 text-center',
        self::userSuperAdmin => 'navbar-expand-md navbar-dark bg-danger fixed-top p-0 text-center',
        self::userCustomer => 'navbar-expand-md navbar-dark bg-primary fixed-top p-0',
        self::userExpert => 'navbar-expand-md navbar-dark bg-primary fixed-top p-0',
        self::userSecretary => 'navbar-expand-md navbar-dark bg-primary fixed-top p-0',
        self::userAdmin => 'navbar-expand-md navbar-dark bg-primary fixed-top p-0',
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
            'innerContainerOptions' => ['class' => ''], // Remove default container class
            'renderInnerContainer' => false, // Do not render the inner container
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
                'url' => ["/$lang/".implode('/', $segments)],
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
                'items' => MyMenu::getSuperAdminNavItems(),
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

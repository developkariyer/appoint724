<?php

$params = require __DIR__.'/params.php';
$db = require __DIR__.'/db.php';

$config = [
    'id' => 'basic',
    'name' => 'Appoint 7|24',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],
        'request' => [
            'cookieValidationKey' => '0YJTc1dgmiN5PM8tWAYVb1339QZVhFC5-D',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\Authidentity',
            'enableAutoLogin' => true,
            'authTimeout' => null,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'language/<lang>' => 'site/language',
                'login/<s>' => 'site/login',
                'login' => 'site/login',
                'logout' => 'site/logout',
                'verifyemail/<token>' => 'site/verifyemail',

            ],
        ],
        'session' => [
            'class' => 'yii\web\Session',
        ],
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap5\BootstrapAsset' => [
                    'class' => 'app\assets\CustomBootstrapAsset',
                ],
            ],
        ],
    ],
    'params' => $params,
    'on beforeRequest' => function ($event) {
        if (!Yii::$app->user->isGuest && !empty(Yii::$app->user->identity->user->language)) {
            $language = Yii::$app->user->identity->user->language;
        } elseif (Yii::$app->session->has('language')) {
            $language = Yii::$app->session->get('language');
        }
        if (empty($language) || !isset(Yii::$app->params['supportedLanguages'][$language])) {
            $language = Yii::$app->params['defaultLanguage'];
        }
        Yii::$app->language = $language;
    },
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;

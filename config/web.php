<?php

$params = require __DIR__.'/params.php';
$db = require __DIR__.'/db.php';

$langPattern = implode('|', array_keys($params['supportedLanguages']));

$config = [
    'id' => 'basic',
    'name' => 'Appoint 7|24',
    'language' => 'tr',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'timeZone' => 'UTC',
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
                '' => 'site/index', // URL without any parameters
                "<lang:$langPattern>/?" => 'site/index', // URL with language parameter only, trailing / is optional
                "<lang:$langPattern>/site/?" => 'site/index', // URL with language parameter and site controller only, trailing / is optional
                "<lang:$langPattern>/<action>" => 'site/<action>', // URL with language parameter and action only, default to site controller
                'verifyemail/<token>' => 'site/verifyemail', // URL with token parameter only, used for email verification and non-interactive link login
                "<lang:$langPattern>/site/login/<s>" => 'site/login', // URL with language parameter, site controller and login action with s as login scenario parameter
                "<lang:$langPattern>/<controller:\w+>/<action:\w+>" => '<controller>/<action>', // URL with language parameter, controller and action, valid for all situations
                '<path:.*>' => 'site/reroute', // URL without any matching rule, site controller reroute action will apply language and redirect to a valid URL
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

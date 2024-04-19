<?php
/** @var array $params */
$langPattern = implode('|', array_keys($params['supportedLanguages']));
$roles = implode('|', array_keys($params['roles']));

return [

    '' => 'site/index',
    "<lang:$langPattern>/?" => 'site/index',
    "<lang:$langPattern>/site/?" => 'site/index',
    "<lang:$langPattern>/<action>" => 'site/<action>',

    'verify/<token>' => 'site/verify',

    "<lang:$langPattern>/site/login/<s>" => 'site/login',

    "<lang:$langPattern>/business/user/<role:$roles>/<slug>" => 'business/user',
    "<lang:$langPattern>/business/<action:\w+>/<slug>" => 'business/<action>',

    "<lang:$langPattern>/user/add/<role:$roles>/<slug>/<id>" => 'user/add',
    "<lang:$langPattern>/user/add/<role:$roles>/<slug>" => 'user/add',
    "<lang:$langPattern>/user/<action:\w+>/<id>" => 'user/<action>',

    "<lang:$langPattern>/<controller:\w+>/<action:\w+>" => '<controller>/<action>',

    '<path:.*>' => 'site/reroute',

];
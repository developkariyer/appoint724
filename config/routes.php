<?php
/** @var array $params */
$langPattern = implode('|', array_keys($params['supportedLanguages']));
$userTypes = implode('|', $params['userTypes']);

return [

    // Acceptable URLs with missing elements
    '' => 'site/index',
    "<lang:$langPattern>/?" => 'site/index',
    "<lang:$langPattern>/site/?" => 'site/index',
    "<lang:$langPattern>/<action>" => 'site/<action>',

    // URL with token parameter only, used for email verification and non-interactive link login
    'verify/<token>' => 'site/verify',

    // Login page scenarios
    "<lang:$langPattern>/site/login/<s>" => 'site/login',

    // Parameter configs for Business with slug, 
    "<lang:$langPattern>/business/<action:\w+>/<slug>" => 'business/<action>',

    // Parameter configs for Business with slug, 
    "<lang:$langPattern>/business/user/<userType:$userTypes>/<slug>" => 'business/user',

    // Parameter configs for User with id,
    "<lang:$langPattern>/user/<action:\w+>/<id>" => 'user/<action>',

    // URL with language parameter, controller and action, valid for all situations
    "<lang:$langPattern>/<controller:\w+>/<action:\w+>" => '<controller>/<action>',

    // URL without any matching rule, site controller reroute action will apply language and redirect to a valid URL
    '<path:.*>' => 'site/reroute',

];
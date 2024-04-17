<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'bsVersion' => '5.x',
    'supportedLanguages' => ['tr' => 'Türkçe', 'en' => 'English'], //, 'de' => 'Deutsch', 'az' => 'Azari', 'ar' => 'Arabic'],
    'roles' => [
        'admin' => Yii::t('app', 'Admin'),
        'secretary' => Yii::t('app', 'Secretary'),
        'expert' => Yii::t('app', 'Expert'),
        'customer' => Yii::t('app','Customer'),
    ],
    'defaultLanguage' => 'tr',
    'meta_description' => 'Appointment Management Application for Small and Medium Businesses and Individual Service Providers like doctors.',
];

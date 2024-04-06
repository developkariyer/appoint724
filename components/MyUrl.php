<?php

namespace app\components;

use yii;
use yii\helpers\Url;

class MyUrl extends Url
{
    public static function to($url = '', $scheme = false)
    {
        // Get the current application language
        $currentLang = Yii::$app->language;

        // If the URL is an array, prepend the language to the route
        if (is_array($url)) {
            if (isset($url[0])) {
                $url[0] = '/' . $currentLang . '/'. $url[0];
            }
        } elseif (is_string($url)) {
            // If the URL is a string, ensure it starts with a '/'
            if (strpos($url, '/') !== 0) {
                $url = '/' . $url;
            }
            // Prepend the language to the URL string
            $url = '/' . $currentLang . '/' .  $url;
        }

        // Call the parent method with the modified URL
        return parent::to($url, $scheme);
    }
}

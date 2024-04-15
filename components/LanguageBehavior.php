<?php

namespace app\components;

use Yii;
use yii\base\Behavior;
use yii\web\Controller;
use yii\helpers\Url;

class LanguageBehavior extends Behavior
{
    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'applyLanguage',
        ];
    }

    public function applyLanguage($event)
    {
        // This is a non-interactive login method. In all cases it will redirect to another page, which this behavior will be called again.
        if (Yii::$app->request->get('token')) {
            return true;
        }

        $language = Yii::$app->request->get('lang');

        if (!$language || !isset(Yii::$app->params['supportedLanguages'][$language])) {
            // we do not have a language parameter in the URL or it is not valid
            if (Yii::$app->session->has('lang')) {
                // if we have a language stored in a session, use it
                $language = Yii::$app->session->get('lang');
            } elseif (!Yii::$app->user->isGuest && !empty(Yii::$app->user->identity->user->language)) {
                // we do not have a session so if the user is logged in and has a language set, use it
                $language = Yii::$app->user->identity->user->language;
            } 
            if (!$language || !isset(Yii::$app->params['supportedLanguages'][$language])) {
                // if we still do not have a valid language, use the system default language
                $language = Yii::$app->params['defaultLanguage'];
            }
        }

        // set the application language and store it in a session
        Yii::$app->session->set('lang', $language);
        Yii::$app->language = $language;
    }
}

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
        $language = Yii::$app->request->get('lang');
        $token = Yii::$app->request->get('token');

        if ($token) {
            return true;
        }

        if (!$language || !isset(Yii::$app->params['supportedLanguages'][$language])) {
            if (Yii::$app->session->has('lang')) {
                $language = Yii::$app->session->get('lang');
            } elseif (!Yii::$app->user->isGuest && !empty(Yii::$app->user->identity->user->language)) {
                $language = Yii::$app->user->identity->user->language;
            } 
            if (!$language || !isset(Yii::$app->params['supportedLanguages'][$language])) {
                $language = Yii::$app->params['defaultLanguage'];
            }
        }

        Yii::$app->session->set('lang', $language);
        Yii::$app->language = $language;
    }
}

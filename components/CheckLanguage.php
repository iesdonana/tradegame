<?php

namespace app\components;

use Yii;

class CheckLanguage extends \yii\base\Behavior
{
    public function events()
    {
        return [
            \yii\web\Application::EVENT_BEFORE_REQUEST => 'changeLanguage'
        ];
    }

    public function changeLanguage()
    {
        if (Yii::$app->request->cookies->has('lang')) {
            Yii::$app->language = Yii::$app->request->cookies->get('lang');
        }
    }
}

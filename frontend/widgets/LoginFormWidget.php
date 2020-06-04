<?php

namespace frontend\widgets;

use frontend\models\forms\LoginForm;
use yii\bootstrap\Widget;

class LoginFormWidget extends Widget
{

    public function run()
    {
        if (\Yii::$app->user->isGuest) {
            $model = new LoginForm();
            return $this->render('loginFormWidget', compact('model'));
        } else {
            return;
        }
    }
}

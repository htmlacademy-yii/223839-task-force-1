<?php

namespace frontend\controllers;

use frontend\models\Cities;
use frontend\models\forms\LoginForm;
use frontend\models\forms\SignupForm;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class SignupController extends Controller
{
    public function actionRegister()
    {
        $model = new SignupForm();

        if (\Yii::$app->request->isPost) {
            $model->load(\Yii::$app->request->post());

            if ($model->validate() && $model->register()) {
                \Yii::$app->session->setFlash('success', 'You have successfully registered');
                return $this->goHome();
            } else {
                \Yii::$app->session->setFlash('error', 'error has occurred, try again');
            }
        }

        $cities = ArrayHelper::map(Cities::find()->asArray()->all(), 'id', 'name');

        return $this->render('register', compact('model', 'cities'));
    }

    public function actionlogin()
    {
        $model = new LoginForm();

        return $this->render('loginForm', compact('model'));
    }
}

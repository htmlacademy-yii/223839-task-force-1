<?php

namespace frontend\controllers;

use frontend\models\Tasks;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public function actionIndex()
    {
        if (!\Yii::$app->user->isGuest) {
            $this->redirect('/tasks');
        }

        $this->layout = 'landing';

        $tasks = Tasks::find()->limit(4)->all();

        return $this->render('index', compact('tasks'));
    }

    public function actionError()
    {
        $exception = \Yii::$app->errorHandler->exception;

        if ($exception !== null) {
//            return \Yii::$app->user->isGuest
//              ? $this->redirect('/')
//              : $this->render('error', compact('exception'));
            return $this->render('error', compact('exception'));
        }
    }
}

<?php

namespace frontend\controllers;

use frontend\models\Tasks;
use yii\web\Controller;

class TasksController extends Controller
{
    const STATUS_NEW = 1;
    const STATUS_CANCELED = 2;
    const STATUS_ACTIVE = 3;
    const STATUS_COMPLETED = 4;
    const STATUS_FAILED = 5;

    public function actionIndex()
    {
        $this->view->title = "TaskForce";

        $status = self::STATUS_NEW;

        $tasks = Tasks::find()
            ->where("status = {$status}")
            ->orderBy('created_at DESC')
            ->with(['city', 'category'])
            ->asArray()
            ->all();

        return $this->render('index', compact('tasks'));
    }
}
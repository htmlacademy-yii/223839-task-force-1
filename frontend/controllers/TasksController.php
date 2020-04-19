<?php

namespace frontend\controllers;

use frontend\models\Tasks;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $tasks = Tasks::find()
            ->andWhere([
                'status' => Tasks::STATUS_NEW
            ])
            ->joinWith([
                'city',
                'category'
            ])
            ->orderBy([
                'created_at' => SORT_DESC
            ])
            ->all();

        return $this->render('index', compact('tasks'));
    }
}
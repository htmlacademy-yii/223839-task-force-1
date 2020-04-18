<?php

namespace frontend\controllers;

use frontend\models\Cities;
use frontend\models\Tasks;
use Logic\Task;
use yii\db\Query;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $tasks = Tasks::find()
            ->select([
                'title',
                'created_at',
                'description',
                'budget',
                'address',
                'city_id',
                'category_id'
            ])
            ->andWhere('status = ' . Tasks::STATUS_NEW)
            ->joinWith(
                [
                    'city',
                    'category'
                ], false)
            ->addSelect([
                'cities.name as city',
                'categories.name as categoryName',
                'categories.icon as categoryIcon'
            ])
            ->orderBy('created_at DESC')
            ->asArray()
            ->all();

        return $this->render('index', compact('tasks'));
    }
}
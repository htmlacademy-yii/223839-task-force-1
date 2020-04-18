<?php

namespace frontend\controllers;

use frontend\models\Tasks;
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
//            ->joinWith(
//                [
//                    'cityName',
//                    'category'
//                ], true)
            ->orderBy('created_at DESC')
            ->all();

        return $this->render('index', compact('tasks'));
    }
}
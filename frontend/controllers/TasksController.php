<?php

namespace frontend\controllers;

use frontend\models\Categories;
use frontend\models\Tasks;
use frontend\models\TasksFilterForms;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


class TasksController extends Controller
{
    /**
     * * Список заданий включает только задания в статусе «Новое».
     * Показываются только задания без привязыки к адресу,
     *
     * TODO а также из города пользователя, либо из города, выбранного пользователем в текущей сессии.
     *
     */
    public function actionIndex()
    {
        $categories = Categories::find()
          ->select(['id', 'name'])
          ->asArray()
          ->all();

        $searchModel = new TasksFilterForms();

        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        $pagination = $dataProvider->getPagination();
        $tasks = $dataProvider->getModels();

        return $this->render('index', compact('tasks', 'categories', 'searchModel', 'pagination'));
    }

    public function actionView(int $id)
    {
        $task = Tasks::findOne($id);

        if (!$task) {
            throw new NotFoundHttpException();
        }

        return $this->render('view', compact('task'));
    }
}


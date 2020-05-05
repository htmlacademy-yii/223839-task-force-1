<?php

namespace frontend\controllers;

use frontend\models\Categories;
use frontend\models\Tasks;
use frontend\models\TasksFilterForms;
use yii\helpers\ArrayHelper;
use yii\web\Controller;


class TasksController extends Controller
{
    /**
     * * Список заданий включает только задания в статусе «Новое».
     * Показываются только задания без привязыки к адресу,
     *
     * TODO а также из города пользователя, либо из города, выбранного пользователем в текущей сессии.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $tasks = Tasks::find()
            ->andWhere(['status' => Tasks::STATUS_NEW])
            ->with(['city', 'category', 'responses'])
            ->orderBy(['created_at' => SORT_DESC]);

        $categories = ArrayHelper::map(Categories::find()->all(), 'id', 'name');
        $filterForm = new TasksFilterForms();

        if (\Yii::$app->request->getIsGet()) {
            $requestData = \Yii::$app->request->get();
            if ($filterForm->load($requestData) && $filterForm->validate()) {
                $filterForm->setData($requestData);
                $filterForm->setFilters($tasks);
            }
        }
        $tasks = $tasks->all();
        return $this->render('index', compact('tasks', 'categories', 'filterForm'));
    }
}


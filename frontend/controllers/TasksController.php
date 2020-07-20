<?php

namespace frontend\controllers;

use frontend\DTO\FilterFormDTO;
use frontend\models\Categories;
use frontend\models\forms\TasksFilterForms;
use frontend\models\Tasks;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;


class TasksController extends SecuredController
{
    /**
     * Список заданий включает только задания в статусе «Новое».
     * Показываются только задания без привязыки к адресу,
     *
     * TODO а также из города пользователя, либо из города, выбранного пользователем в текущей сессии.
     *
     */
    public function actionIndex()
    {
        $categories = ArrayHelper::map(Categories::find()->select(['id', 'name'])->all(), 'id', 'name');

        $searchModel = new TasksFilterForms();
        $query = Tasks::find()
            ->andWhere(['status' => Tasks::STATUS_NEW])
            ->with(['city', 'category', 'responses'])
            ->orderBy(['created_at' => SORT_DESC]);
        $data = Yii::$app->request->queryParams;

        $dataProvider = $searchModel->search(new FilterFormDTO($query, $data));

        $pagination = $dataProvider->getPagination();
        $tasks = $dataProvider->getModels();

        return $this->render('index', compact('tasks', 'categories', 'searchModel', 'pagination'));
    }

    public function actionView(int $id)
    {
        if (($task = Tasks::findOne($id)) === null) {
            throw new NotFoundHttpException('page not found');
        }

        return $this->render('view', compact('task'));
    }
}


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
        $searchModel = new TasksFilterForms();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'tasks'       => $dataProvider->getModels(),
            'pagination'  => $dataProvider->getPagination(),
            'searchModel' => $searchModel,
            'categories'  => ArrayHelper::map(Categories::find()->select(['id', 'name'])->all(), 'id', 'name')
        ]);
    }

    public function actionView(int $id)
    {
        if (($task = Tasks::findOne($id)) === null) {
            throw new NotFoundHttpException('page not found');
        }

        return $this->render('view', compact('task'));
    }
}


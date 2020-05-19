<?php

namespace frontend\controllers;

use frontend\models\Categories;
use frontend\models\Users;
use frontend\models\UsersFiltersForm;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class UsersController extends Controller
{
    public function actionIndex()
    {
        $categories = ArrayHelper::map(Categories::find()->all(), 'id', 'name');

        $searchModel = new UsersFiltersForm();

        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        $pagination = $dataProvider->getPagination();
        $performers = $dataProvider->getModels();

        return $this->render('index', compact('performers', 'categories', 'searchModel', 'pagination'));
    }

    public function actionView(int $id)
    {
        $user = Users::findOne($id);

        if(!$user) {
            throw new NotFoundHttpException();
        }

        return $this->render('view', compact('user'));
    }
}

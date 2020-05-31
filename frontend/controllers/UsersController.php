<?php

namespace frontend\controllers;

use frontend\models\Categories;
use frontend\models\forms\UsersFiltersForm;
use frontend\models\Users;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class UsersController extends Controller
{
    public function actionIndex()
    {
        $categories = ArrayHelper::map(Categories::find()->all(), 'id', 'name');
        $sorts = UsersFiltersForm::getSortsList();

        $searchModel = new UsersFiltersForm();

        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        $pagination = $dataProvider->getPagination();
        $performers = $dataProvider->getModels();

        return $this->render('index', compact('performers', 'categories', 'searchModel', 'pagination', 'sorts'));
    }

    public function actionView(int $id)
    {
        if (!$user = Users::findOne($id)) {
            throw new NotFoundHttpException('page not found');
        }

        return $this->render('view', compact('user'));
    }
}

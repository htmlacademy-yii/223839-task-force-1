<?php

namespace frontend\controllers;

use frontend\models\Categories;
use frontend\models\forms\UsersFiltersForm;
use frontend\models\Users;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class UsersController extends SecuredController
{
    public function actionIndex()
    {
        $searchModel = new UsersFiltersForm();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'categories'  => ArrayHelper::map(Categories::find()->all(), 'id', 'name'),
            'searchModel' => $searchModel,
            'sorts'       => UsersFiltersForm::getSortsList(),
            'performers'  => $dataProvider->getModels(),
            'pagination'  => $dataProvider->getPagination()
        ]);
    }

    public function actionView(int $id)
    {
        $user = Users::findOne($id);

        if ($user === null) {
            throw new NotFoundHttpException('page not found');
        }

        return $this->render('view', compact('user'));
    }
}

<?php

namespace frontend\controllers;

use frontend\models\Categories;
use frontend\models\Users;
use frontend\models\UsersFiltersForm;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class UsersController extends Controller
{
    public function actionIndex(): string
    {
        $performers = Users::find()
            ->andWhere(['role' => Users::ROLE_PERFORMER])
            ->with(
                [
                    'reviewsPerformer',
                    'tasksPerformer',
                    'userSpecializations',
                    'categories'
                ]
            );

        $categories = ArrayHelper::map(Categories::find()->all(), 'id', 'name');

        $filterForm = new UsersFiltersForm();

        if (\Yii::$app->request->getIsGet()) {
            $requestData = \Yii::$app->request->get();
            if (!empty($requestData['sort'])) {
                $performers = $filterForm->setSort($requestData['sort'], $performers);
            }
            if ($filterForm->load($requestData) && $filterForm->validate()) {
                $filterForm->setData($requestData);
                $filterForm->setFilters($performers);
            }
        }

        $performers = $performers->all();

        return $this->render('index', compact('performers', 'categories', 'filterForm'));
    }
}

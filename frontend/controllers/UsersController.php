<?php

namespace frontend\controllers;

use frontend\models\Users;
use yii\web\Controller;

class UsersController extends Controller
{
    public function actionIndex()
    {
        $performers = Users::find()
            ->andWhere(['role' => Users::ROLE_PERFORMER])
            ->with([
                'reviewsPerformer',
                'tasksPerformer',
                'userSpecializations',
                'categories'
            ])
            ->all();


        return $this->render('index', compact('performers'));
    }
}

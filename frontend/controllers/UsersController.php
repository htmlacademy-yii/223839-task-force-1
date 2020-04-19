<?php

namespace frontend\controllers;

use frontend\models\Users;
use yii\web\Controller;

class UsersController extends Controller
{
    public function actionIndex()
    {
        $performers = Users::find()
            ->andWhere([
                'role' => Users::PERFORMER
            ])
            ->joinWith([
                'reviewsPerformer',
                'tasksPerformer',
                'userSpecializations'
            ])
            ->all();


        return $this->render('index', compact('performers'));
    }
}
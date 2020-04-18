<?php

namespace frontend\controllers;

use common\models\User;
use frontend\models\Users;
use yii\web\Controller;

class UsersController extends Controller
{
    public function actionIndex()
    {
        $performers = Users::find()
            ->select([
                'users.id',
                'first_name',
                'last_name',
                'last_activity',
                'biography',
            ])
            ->andWhere("role = '" . Users::PERFORMER . "'")
            ->groupBy('users.id')
            ->limit(6)
            ->all();


        return $this->render('index', compact('performers'));
    }
}
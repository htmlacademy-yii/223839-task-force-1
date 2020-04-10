<?php

namespace frontend\controllers;

use frontend\models\Categories;
use frontend\models\Users;
use yii\web\Controller;

class UsersController extends Controller
{
    const PERFORMER = 'Performer';
    const CUSTOMER = 'Customer';

    public function actionIndex()
    {
        $this->view->title = "TaskForce";

        $userRole = self::PERFORMER;

        $users = Users::find()
            ->select(['id', 'first_name', 'last_name', 'biography', 'last_activity'])
            ->where("role = :userRole", ['userRole' => $userRole])
            ->orderBy('date_joined DESC')
            ->with(["reviews", "tasks{$userRole}", "categories", "rating"])
            ->asArray()
            ->all();


        return $this->render('index', compact('users', 'userRole'));
    }
}
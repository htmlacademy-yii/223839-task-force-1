<?php

namespace frontend\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

class SecuredController extends Controller
{
    public function behaviors()
    {
        return [
          'access' => [
            'class' => AccessControl::class,
            'denyCallback' => function ($rule, $action) {
                return $this->redirect('/');
            },
            'rules' => [
              [
                'allow' => false,
                'roles' => ['?'],
              ],
              [
                'allow'       => true,
                'roles'       => ['@'],
                'controllers' => ['users', 'tasks'],
              ]
            ],
          ],
        ];
    }
}

<?php

namespace frontend\controllers;

use frontend\models\Cities;
use frontend\models\forms\LoginForm;
use frontend\models\forms\SignupForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

class AuthController extends Controller
{
    public function behaviors()
    {
        return [
          'access' => [
            'class'        => AccessControl::class,
            'only'         => ['login', 'register', 'logout'],
            'denyCallback' => function ($rule, $action) {
                return $this->redirect('/');
            },
            'rules'        => [
              [
                'allow'   => true,
                'actions' => ['login', 'register'],
                'roles'   => ['?']
              ],
              [
                'allow'   => true,
                'actions' => ['logout'],
                'roles'   => ['@']
              ]
            ]
          ],
          'verbs'  => [
            'class'   => VerbFilter::class,
            'actions' => [
              [
                'login'    => ['POST'],
                'register' => ['POST'],
                'logout'   => ['POST']
              ]
            ]
          ]
        ];
    }

    public function actionRegister()
    {
        $model = new SignupForm();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            if ($model->register()) {
                Yii::$app->session->setFlash('success', 'You have successfully registered');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'error has occurred, try again');
            }
        }

        $cities = ArrayHelper::map(Cities::find()->asArray()->all(), 'id', 'name');

        return $this->render('register', compact('model', 'cities'));
    }

    public function actionLogin()
    {
        $model = new LoginForm();

        if (Yii::$app->request->isPost && Yii::$app->request->isAjax && $model->load(($data = Yii::$app->request->post()))) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            ActiveForm::validate($model);
            if ($model->validate($data, false)) {
                Yii::$app->user->login($model->getUser());
            } else {
                return ActiveForm::validate($model);
            }
        }

        return $this->redirect('/');
    }


    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}

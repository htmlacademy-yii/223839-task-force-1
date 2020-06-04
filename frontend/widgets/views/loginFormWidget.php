<?php
/**
 * @var $model \frontend\models\forms\LoginForm
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="header__account--index">
    <a href="#"
       class='header__account-enter open-modal'
       data-for='enter-form'>
        <span>Вход</span>
    </a>
    или
    <?= Html::a('Регистрация', ['auth/register'], ['class' => 'header__account-registration']) ?>
</div>

<section class="modal enter-form form-modal" id="enter-form">
    <h2>Вход на сайт</h2>
    <?php $form = ActiveForm::begin([
      'method'                 => 'POST',
      'errorCssClass'          => 'input-danger',
      'enableClientValidation' => true,
      'action'                 => ['auth/login'],
      'options'                => [
        'id'    => 'loginForm',
        'class' => 'login-form'
      ]
    ]) ?>
    <p>
        <?= $form->errorSummary($model) ?>

        <?= $form
          ->field($model, 'email', ['enableAjaxValidation' => true])
          ->input('email', [
            'autofocus' => true,
            'class'     => 'enter-form-email input input-middle',
            'style'     => 'margin-bottom: 5px'
          ])
          ->label('Email', ['class' => 'form-modal-description'])
          ->error([
            'tag'   => 'small',
            'style' => 'display: block; padding-bottom: 20px; padding-right: 15px'
          ])
        ?>
    </p>
    <p>
        <?= $form
          ->field($model, 'password', ['enableAjaxValidation' => true,])
          ->passwordInput([
            'class' => 'enter-form-email input input-middle',
            'style' => 'margin-bottom: 5px',
          ])
          ->label('Пароль', ['class' => 'form-modal-description'])
          ->error([
            'tag'   => 'small',
            'style' => 'display: block; padding-bottom: 20px; padding-right: 15px'
          ])
        ?>
    </p>

    <button class="button" id="login_button" name="login_submit" type="submit" value="login">Войти</button>
    <?php $form::end() ?>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>


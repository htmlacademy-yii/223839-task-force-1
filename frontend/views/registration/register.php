<?php

/* @var $this yii\web\View
 * @var $cities \frontend\models\Cities
 * @var $model \frontend\models\forms\SignupForm
 *
 */

use yii\helpers\Html;

$this->title = 'Registration';
?>
<section class="registration__user">
    <h1>Регистрация аккаунта</h1>
    <div class="registration-wrapper">

        <?php
        $form = \yii\widgets\ActiveForm::begin([
          'method' => 'POST',
          'action' => ['/signup'],
          'id' => 'SignUp-form',
          'errorCssClass' => 'input-danger',
          'options' => [
            'class' => 'registration__user-form form-create'
          ]
        ]) ?>

        <?= $form
          ->field($model, 'email')
          ->textarea([
            'class' => 'input textarea',
            'rows' => 1,
            'placeholder' => 'user@mail.ru',
            'autofocus' => true,
          ])
          ->label('Электронная почта')
          ->error(['tag' => 'span'])
          ->hint('Введите валидный адрес электронной почты', ['tag' => 'span'])
        ?>

        <?= $form
          ->field($model, 'user_name')
          ->textarea([
            'class' => 'input textarea',
            'rows' => 1,
            'placeholder' => 'Ваше Имя'
          ])->label('Ваше имя и фамилия')
          ->error(['tag' => 'span'])
          ->hint('Введите ваше имя и фамилию', ['tag' => 'span'])
        ?>


        <?= $form
          ->field($model, 'city_id')
          ->dropDownList($cities, [
            'class' => 'multiple-select input town-select registration-town',
            'size' => 1
          ])->label('Город проживания')
          ->error(['tag' => 'span'])
          ->hint('Укажите город, чтобы находить подходящие задачи', ['tag' => 'span'])
        ?>

        <?= $form
          ->field($model, 'password')
          ->passwordInput(['class' => 'input textarea'])
          ->label('Пароль')
          ->error(['tag' => 'span'])
          ->hint('Длина пароля от 8 символов', ['tag' => 'span'])
        ?>

        <?= Html::submitButton('Создать аккаунт', ['class' => 'button button__registration']) ?>

        <?php
        $form::end() ?>
    </div>

</section>


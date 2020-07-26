<?php

/* @var $this yii\web\View */

/* @var $name string */
/* @var $message string */

/* @var $exception Exception */

use yii\helpers\Html;

$message = $exception->getMessage();

$this->title = 'error';
?>
<div class="site-error">

    <h1> <?= nl2br(Html::encode($message)) ?> </h1>


</div>

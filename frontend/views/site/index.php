<?php

/**
 * @var $this       yii\web\View
 * @var $tasks      \frontend\models\Tasks
 * @var $task       \frontend\models\Tasks
 */

use yii\helpers\Html;

$this->title = 'TaskForce';
if (Yii::$app->user->isGuest) :
    foreach ($tasks as $task) : ?>
        <div class="landing-task">
            <?php
            $cutString = function (string $str, int $end): string {
                if (strlen($str) >= $end) {
                    return mb_strimwidth($str, 0, $end) . '...';
                }
                return $str;
            };

            $title       = $cutString(Html::encode($task->title), 20);
            $description = $cutString(Html::encode($task->description), 67);

            $icons = [
              'clean',
              'cargo',
              'courier',
              'event',
            ];
            $icon  = in_array($task->category->icon, $icons) ? $task->category->icon : 'courier'
            ?>
            <div class="landing-task-top  task-<?= $icon ?>"></div>
            <div class="landing-task-description">
                <h3>
                    <?= Html::a(
                      $title,
                      ['/tasks/view', 'id' => $task->id],
                      ['class' => 'link-regular']) ?>
                </h3>
                <p><?= $description ?></p>
            </div>
            <div class="landing-task-info">
                <div class="task-info-left">
                    <p>
                        <?= Html::a($task->category->name, ['#'], ['class' => 'link-regular']) ?>
                    </p>
                    <p><?= Yii::$app->formatter->asRelativeTime($task->created_at) ?></p>
                </div>
                <span><?= Html::encode($task->budget) ?> <b>₽</b></span>
            </div>
        </div>
    <?php endforeach; endif; ?>

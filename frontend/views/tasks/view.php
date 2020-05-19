<?php
/* @var $this yii\web\View
 * @var $task \frontend\models\Tasks
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

$this->title = $task->title;
?>

<section class="content-view">
    <div class="content-view__card">
        <div class="content-view__card-wrapper">
            <div class="content-view__header">
                <div class="content-view__headline">
                    <?= Html::tag('h1', Html::encode($task->title)) ?>
                    <span>Размещено в категории
                        <?= Html::a(
                          Html::encode($task->category->name),
                          ['#'],
                          ['class' => 'link-regular']
                        ) ?>
                        <?= Yii::$app->formatter->asRelativeTime($task->created_at) ?>
                    </span>
                </div>
                <b class="new-task__price new-task__price--<?= $task->category->icon ?> content-view-price">
                    <?= Html::encode($task->budget) ?> <b>₽</b>
                </b>
                <div class="new-task__icon new-task__icon--<?= $task->category->icon ?> content-view-icon"></div>
            </div>
            <div class="content-view__description">
                <h3 class="content-view__h3">Общее описание</h3>
                <p><?= HTMLPurifier::process($task->description) ?></p>
            </div>
            <div class="content-view__attach">
                <h3 class="content-view__h3">Вложения</h3>
                <a href="#">my_picture.jpeg</a>
                <a href="#">agreement.docx</a>
            </div>
            <div class="content-view__location">
                <h3 class="content-view__h3">Расположение</h3>
                <div class="content-view__location-wrapper">
                    <div class="content-view__map">
                        <a href="#"><img src="../img/map.jpg" width="361" height="292"
                                         alt="Москва, Новый арбат, 23 к. 1"></a>
                    </div>
                    <div class="content-view__address">
                        <span class="address__town">Москва</span><br>
                        <span>Новый арбат, 23 к. 1</span>
                        <p>Вход под арку, код домофона 1122</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-view__action-buttons">
            <button class=" button button__big-color response-button open-modal"
                    type="button" data-for="response-form">Откликнуться
            </button>
            <button class="button button__big-color refusal-button open-modal"
                    type="button" data-for="refuse-form">Отказаться
            </button>
            <button class="button button__big-color request-button open-modal"
                    type="button" data-for="complete-form">Завершить
            </button>
        </div>
    </div>
    <div class="content-view__feedback">

        <h2>Отклики <span>(<?= count(ArrayHelper::toArray($task->responses, ['id'])); ?>)</span></h2>

        <div class="content-view__feedback-wrapper">
            <?php
            foreach ($task->responses as $response) : ?>
                <div class="content-view__feedback-card">
                    <div class="feedback-card__top">
                        <a href="#"><img src="../img/man-glasses.jpg" width="55" height="55"></a>
                        <div class="feedback-card__top--name">
                            <p>
                                <?= Html::a(
                                  Html::encode("{$response->performer->first_name} {$response->performer->last_name}"),
                                  ['users/view', 'id' => $response->performer_id],
                                  ['class' => 'link-regular']
                                ) ?>
                            </p>
                            <?= $response->performer->getPerformerRating(['withStars' => true]) ?>
                        </div>
                        <?= Html::tag(
                          'span',
                          Yii::$app->formatter->asRelativeTime($response->response_date),
                          ['class' => 'new-task__time']
                        ) ?>
                    </div>
                    <div class="feedback-card__content">
                        <p><?= Html::encode($response->text) ?></p>
                        <span><?= Html::encode($response->offer_price) ?> ₽</span>
                    </div>
                    <div class="feedback-card__actions">
                        <a class="button__small-color request-button button"
                           type="button">Подтвердить</a>
                        <a class="button__small-color refusal-button button"
                           type="button">Отказать</a>
                    </div>
                </div>
            <?php
            endforeach;
            ?>
        </div>
    </div>
    <pre>
</section>
<section class="connect-desk">
    <div class="connect-desk__profile-mini">
        <div class="profile-mini__wrapper">
            <h3>Заказчик</h3>
            <div class="profile-mini__top">
                <img src="../img/man-brune.jpg" width="62" height="62" alt="Аватар заказчика">
                <div class="profile-mini__name five-stars__rate">
                    <p>
                        <?= Html::encode("{$task->author->first_name} {$task->author->last_name} ") ?>
                    </p>
                </div>
            </div>
            <p class="info-customer">
                <span><?= $task->getTasksCounter(count($task->author->tasksCustomer), ['withWord' => true]) ?></span>
                <span class="last-">
                    <?= $task->author->getCountYearsOnSite(['withWord' => true]) ?> на сайте
                </span>
            </p>
            <?= Html::a('Смотреть профиль',
              ['users/view', 'id' => $task->author_id],
              ['class' => 'link-regular']
            )
            ?>
        </div>
    </div>
    <div class="connect-desk__chat">
        <h3>Переписка</h3>
        <div class="chat__overflow">
            <div class="chat__message chat__message--out">
                <p class="chat__message-time">10.05.2019, 14:56</p>
                <p class="chat__message-text">Привет. Во сколько сможешь
                    приступить к работе?</p>
            </div>
            <div class="chat__message chat__message--in">
                <p class="chat__message-time">10.05.2019, 14:57</p>
                <p class="chat__message-text">На задание
                    выделены всего сутки, так что через час</p>
            </div>
            <div class="chat__message chat__message--out">
                <p class="chat__message-time">10.05.2019, 14:57</p>
                <p class="chat__message-text">Хорошо. Думаю, мы справимся</p>
            </div>
        </div>
        <p class="chat__your-message">Ваше сообщение</p>
        <form class="chat__form">
            <textarea class="input textarea textarea-chat" rows="2" name="message-text"
                      placeholder="Текст сообщения"></textarea>
            <button class="button chat__button" type="submit">Отправить</button>
        </form>
    </div>
</section>

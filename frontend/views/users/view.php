<?php
/* @var $this yii\web\View
 * @var $user \frontend\models\Users;
 */

use yii\helpers\Html;

$this->title = Html::encode($user->first_name) . ' ' . Html::encode($user->last_name);
?>

<section class="content-view">
    <div class="user__card-wrapper">
        <div class="user__card">
            <img src="../img/man-hat.png" width="120" height="120" alt="Аватар пользователя">
            <div class="content-view__headline">
                <h1><?= Html::encode($user->first_name) . ' ' . Html::encode($user->last_name) ?></h1>

                <p>Россия, <?= $user->city->name ?>, <?= $user->getAge(['withWord' => true]) ?></p>

                <div class="profile-mini__name five-stars__rate">
                    <?= $user->getPerformerRating(['withStars' => true]) ?>
                </div>
                <b class="done-task">Получил <?= $user->getCountTasks(['withWord' => true]) ?></b>
                <b class="done-review">Получил <?= $user->getCountReviews(['withWord' => true]) ?></b>
            </div>
            <div class="content-view__headline user__card-bookmark user__card-bookmark--current">
                <?= Html::tag('span',
                  'Был на сайте ' . Yii::$app->formatter->asRelativeTime($user->last_activity)
                ) ?>
                <a href="#"><b></b></a>
            </div>
        </div>
        <div class="content-view__description">
            <p><?= Html::encode($user->biography) ?></p>
        </div>
        <div class="user__card-general-information">
            <div class="user__card-info">
                <h3 class="content-view__h3">Специализации</h3>
                <div class="link-specialization">

                    <?php
                    foreach ($user->getCategories()->all() as $category) : ?>
                        <?= Html::a($category->name, ['#'], ['class' => 'link-regular']) ?>
                    <?php
                    endforeach; ?>

                </div>
                <h3 class="content-view__h3">Контакты</h3>
                <div class="user__card-link">
                    <a class="user__card-link--tel link-regular" href="#"><?= Html::encode($user->phone) ?></a>
                    <?= Yii::$app->formatter->asEmail(Html::encode($user->email),
                      ['class' => 'user__card-link--email link-regular'])
                    ?>
                    <a class="user__card-link--skype link-regular" href="#"><?= Html::encode($user->skype) ?></a>
                </div>
            </div>
            <div class="user__card-photo">
                <h3 class="content-view__h3">Фото работ</h3>
                <a href="#"><img src="../img/rome-photo.jpg" width="85" height="86" alt="Фото работы"></a>
                <a href="#"><img src="../img/smartphone-photo.png" width="85" height="86" alt="Фото работы"></a>
                <a href="#"><img src="../img/dotonbori-photo.png" width="85" height="86" alt="Фото работы"></a>
            </div>
        </div>
    </div>
    <div class="content-view__feedback">
        <h2>Отзывы<span>(<?= $user->countReviews ?>)</span></h2>
        <div class="content-view__feedback-wrapper reviews-wrapper">
            <?php
            foreach ($user->reviewsPerformer as $review) : ?>
                <div class="feedback-card__reviews">
                    <p class="link-task link">Задание
                        <?= Html::a(
                          Html::encode($review->task->title),
                          ['tasks/view', 'id' => $review->task_id],
                          ['class' => 'link-regular']
                        ) ?>
                    </p>
                    <div class="card__review">
                        <a href="#"><img src="../img/man-glasses.jpg" width="55" height="54"></a>
                        <div class="feedback-card__reviews-content">
                            <p class="link-name link">
                                <?= Html::a(
                                  Html::encode($review->customer->first_name) . ' ' . ($review->customer->last_name),
                                  ['users/view', 'id' => $review->customer->id],
                                  ['class' => 'link-regular']
                                ) ?>
                            </p>
                            <p class="review-text">
                                <?= Html::encode($review->text) ?>
                            </p>
                        </div>
                        <div class="card__review-rate">
                            <p class="<?= $review->rating > 3 ? 'five-rate' : 'three-rate' ?> big-rate">
                                <?= Html::encode($review->rating) ?><span></span>
                            </p>
                        </div>
                    </div>
                </div>
            <?php
            endforeach; ?>
        </div>
    </div>
</section>
<section class="connect-desk">
    <div class="connect-desk__chat">

    </div>
</section>

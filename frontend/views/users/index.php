<?php
/* @var $this yii\web\View
 * @var $performers \frontend\models\Users
 * @var $filterForm \frontend\models\UsersFiltersForm
 * @var $categories \frontend\models\Categories
 */

use \yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\widgets\ActiveForm;

$this->title = "TaskForce";
?>
<section class="user__search">
    <div class="user__search-link">
        <p>Сортировать по:</p>
        <ul class="user__search-list">
            <li class="user__search-item user__search-item--current">
                <a href="/users/sort_rating" class="link-regular">Рейтингу</a>
            </li>
            <li class="user__search-item">
                <a href="/users/sort_orders" class="link-regular">Числу заказов</a>
            </li>
            <li class="user__search-item">
                <a href="/users/sort_popular" class="link-regular">Популярности</a>
            </li>
        </ul>
    </div>


    <?php foreach ($performers as $performer) : ?>
        <div class="content-view__feedback-card user__search-wrapper">
            <div class="feedback-card__top">
                <div class="user__search-icon">
                    <a href="#"><img src="../img/man-glasses.jpg" width="65" height="65"></a>
                    <span><?= count($performer->tasksPerformer) ?> заданий</span>
                    <span><?= count($performer->reviewsPerformer) ?> отзывов</span>
                </div>
                <div class="feedback-card__top--name user__search-card">
                    <p class="link-name">
                        <a href="#" class="link-regular">
                            <?= Html::encode($performer->first_name) ?> <?= Html::encode($performer->last_name) ?>
                        </a>
                    </p>
                    <?php
                    $rating = $performer->performerRating;
                    for ($i = 0; $i < 5; $i++) {
                        if ($i < floor($rating)) {
                            echo ' <span></span > ';
                        } else {
                            echo '<span class="star-disabled" ></span > ';
                        }
                    }
                    echo "<b>{$rating}<b>";
                    ?>
                    <p class="user__search-content">
                        <?= HtmlPurifier::process($performer->biography) ?>
                    </p>
                </div>
                <span class="new-task__time">Был на сайте
                    <?= Yii::$app->formatter->asRelativeTime($performer->last_activity) ?></span>
            </div>
            <div class="link-specialization user__search-link--bottom">
                <?php foreach ($performer->categories as $category) : ?>
                    <a href="#" class="link-regular"><?= $category->name ?></a>
                <?php endforeach ?>
            </div>
        </div>
    <?php endforeach; ?>
</section>
<section class="search-task">
    <div class="search-task__wrapper">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'action' => ['users/search'],
            'options' => [
                'class' => 'search-task__form',
            ]
        ]);
        $loadCategories = $filterForm->categories;
        $loadExtraFields = $filterForm->extraFields;
        ?>

        <?= Html::beginTag('fieldset', ['class' => 'search-task__categories']) ?>
        <?= Html::tag('legend', 'Категории') ?>

        <?= $form->field($filterForm, 'categories')
            ->label(false)
            ->checkboxList($categories, [
                'item' => function (
                    int $index,
                    string $label,
                    string $name,
                    bool $checked,
                    string $value
                ) use ($loadCategories) : string {
                    $checked = ($checked === true) ? 'checked' : '';
                    $id = "category-{$index}";

                    return "<input type='checkbox' id='{$id}' name='{$name}'
                    class='visually-hidden checkbox__input' value='{$value}' {$checked}>
                    <label for='{$id}'>{$label}</label>";
                }
            ]) ?>

        <?= Html::endTag('fieldset') ?>


        <?= Html::beginTag('fieldset', ['class' => 'search-task__categories']) ?>
        <?= Html::tag('legend', 'Дополнительно') ?>


        <?= $form->field($filterForm, 'extraFields')
            ->label(false)
            ->checkboxList($filterForm::getExtraFieldsList(), [
                'item' => function (
                    int $index,
                    string $label,
                    string $name,
                    bool $checked,
                    string $value
                ) use ($loadExtraFields) : string {
                    $checked = ($checked === true) ? 'checked' : '';
                    $id = "extraFields-{$index}";

                    return "<input type='checkbox' id='{$id}' name='{$name}'
                    class='visually-hidden checkbox__input' value='{$value}' {$checked}>
                    <label for='{$id}'>{$label}</label>";
                }
            ]) ?>

        <?= Html::endTag('fieldset') ?>
        <?= $form
            ->field($filterForm, 'search', ['options' => ['tag' => false]])
            ->input('search', ['class' => 'input-middle input'])
            ->label('Поиск по имени', ['class' => 'search-task__name']) ?>
        <?= Html::submitButton('Искать', ['class' => 'button']) ?>
        <?php $form::end() ?>

    </div>
</section>

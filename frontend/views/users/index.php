<?php
/* @var $this yii\web\View
 * @var $performers \frontend\models\Users
 * @var $searchModel \frontend\models\UsersFiltersForm
 * @var $categories \frontend\models\Categories
 * @var $pagination \yii\data\Pagination
 */

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\widgets\ActiveForm;

$this->title = "TaskForce";
?>
<section class="user__search">
    <div class="user__search-link">
        <p>Сортировать по:</p>
        <ul class="user__search-list">
            <li class="user__search-item user__search-item--current">
                <a href="/users/?sort=rating" class="link-regular">Рейтингу</a>
            </li>
            <li class="user__search-item">
                <a href="/users/?sort=orders" class="link-regular">Числу заказов</a>
            </li>
            <li class="user__search-item">
                <a href="/users/?sort=popular" class="link-regular">Популярности</a>
            </li>
        </ul>
    </div>

    <?php
    foreach ($performers as $performer) : ?>
        <div class="content-view__feedback-card user__search-wrapper">
            <div class="feedback-card__top">
                <div class="user__search-icon">

                    <a href="#"><img src="../img/man-glasses.jpg" width="65" height="65"></a>

                    <span><?= count($performer->tasksPerformer) ?> заданий</span>
                    <span><?= count($performer->reviewsPerformer) ?> отзывов</span>
                </div>
                <div class="feedback-card__top--name user__search-card">
                    <p class="link-name">
                        <?= Html::a(Html::encode($performer->first_name) . ' ' . Html::encode($performer->last_name),
                          ['users/view', 'id' => $performer->id],
                          ['class' => 'link-regular']
                        ) ?>
                    </p>
                    <?= $performer->getPerformerRating(['withStars' => true]) ?>
                    <p class="user__search-content">
                        <?= HtmlPurifier::process($performer->biography) ?>
                    </p>
                </div>
                <span class="new-task__time">Был на сайте
                    <?= Yii::$app->formatter->asRelativeTime($performer->last_activity) ?></span>
            </div>
            <div class="link-specialization user__search-link--bottom">
                <?php
                foreach ($performer->categories as $category) : ?>
                    <a href="#" class="link-regular"><?= $category->name ?></a>
                <?php
                endforeach ?>
            </div>
        </div>
    <?php
    endforeach; ?>

    <div class='new-task__pagination'>
        <?= \yii\widgets\LinkPager::widget([
          'pagination' => $pagination,
          'options' => [
            'class' => 'new-task__pagination-list'
          ],
          'prevPageLabel' => '',
          'nextPageLabel' => '',
          'pageCssClass' => 'pagination__item',
          'prevPageCssClass' => 'pagination__item',
          'nextPageCssClass' => 'pagination__item'
        ]) ?>
    </div>

</section>
<section class="search-task">
    <div class="search-task__wrapper">
        <?php
        $form = ActiveForm::begin([
          'method' => 'get',
          'action' => ['users/search'],
          'options' => [
            'class' => 'search-task__form',
          ]
        ]);
        $loadCategories = $searchModel->categories;
        $loadExtraFields = $searchModel->extraFields;
        ?>

        <?= Html::beginTag('fieldset', ['class' => 'search-task__categories']) ?>
        <?= Html::tag('legend', 'Категории') ?>

        <?= $form->field($searchModel, 'categories')
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


        <?= $form->field($searchModel, 'extraFields')
          ->label(false)
          ->checkboxList($searchModel::getExtraFieldsList(), [
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
          ->field($searchModel, 'search', ['options' => ['tag' => false]])
          ->input('search', ['class' => 'input-middle input'])
          ->label('Поиск по имени', ['class' => 'search-task__name']) ?>
        <?= Html::submitButton('Искать', ['class' => 'button']) ?>

        <?php
        $form::end() ?>

    </div>
</section>

<?php
/* @var $this yii\web\View
 * @var $performer   Users
 * @var $searchModel UsersFiltersForm
 * @var $categories  Categories
 * @var $pagination  Pagination
 */

use frontend\models\Categories;
use frontend\models\forms\UsersFiltersForm;
use frontend\models\Users;
use frontend\widgets\RatingWidget;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

$this->title = "TaskForce";
?>
<section class="user__search">
    <div class="user__search-link">
        <p>Сортировать по:</p>

        <ul class="user__search-list">
            <?php foreach ($sorts as ['name' => $name, 'link' => $link]) : ?>
                <li class="user__search-item <?= $searchModel->sort === $link ? 'user__search-item--current' : '' ?>">
                    <a href="<?= Url::toRoute(['/users', 'sort' => $link]) ?>"
                       class="link-regular">
                        <?= $name ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?php foreach ($performers as $performer) : ?>
        <div class="content-view__feedback-card user__search-wrapper">
            <div class="feedback-card__top">
                <div class="user__search-icon">
                    <a href="#"><img src="../img/man-glasses.jpg" width="65" height="65"></a>
                    <span><?= $performer->getCountTasks(['withWord' => true]) ?></span>
                    <span><?= $performer->getCountReviews(['withWord' => true]) ?></span>
                </div>
                <div class="feedback-card__top--name user__search-card">
                    <p class="link-name">
                        <a href="<?= Url::toRoute(['users/view', 'id' => $performer->id]) ?>"
                           class="link-regular"><?= Html::encode($performer->getFullName()) ?>
                        </a>
                    </p>

                    <?= RatingWidget::widget(['rating' => $performer->rating]) ?>

                    <p class="user__search-content">
                        <?= HtmlPurifier::process($performer->biography) ?>
                    </p>
                </div>
                <span class="new-task__time">Был на сайте
                    <?= Yii::$app->formatter->asRelativeTime($performer->last_activity) ?>
                </span>
            </div>
            <div class="link-specialization user__search-link--bottom">
                <?php foreach ($performer->categories as $category) : ?>
                    <a href="#" class="link-regular"><?= $category->name ?></a>
                <?php endforeach ?>
            </div>
        </div>
    <?php endforeach; ?>

    <div class='new-task__pagination'>
        <?= LinkPager::widget([
            'pagination'       => $pagination,
            'options'          => [
                'class' => 'new-task__pagination-list'
            ],
            'prevPageLabel'    => '',
            'nextPageLabel'    => '',
            'pageCssClass'     => 'pagination__item',
            'prevPageCssClass' => 'pagination__item',
            'nextPageCssClass' => 'pagination__item'
        ]) ?>
    </div>
</section>

<section class="search-task">
    <div class="search-task__wrapper">
        <?php
        $form = ActiveForm::begin([
            'method'  => 'get',
            'action'  => ['users/search'],
            'options' => [
                'class' => 'search-task__form'
            ]
        ]);
        $loadCategories = empty($searchModel->categories) ? [] : $searchModel->categories;
        $loadExtraFields = empty($searchModel->extraFields) ? [] : $searchModel->extraFields;
        ?>

        <fieldset class='search-task__categories'>
            <legend>категории</legend>
            <?php foreach ($categories as $key => $category): ?>
                <input type='checkbox' id='category-<?= $key ?>' name='categories[]<?= $key ?>'
                       class='visually-hidden checkbox__input' value='<?= $key ?>'
                    <?= in_array($key, $loadCategories) ? 'checked' : '' ?>>
                <label for='category-<?= $key ?>'><?= $category ?></label>
            <?php endforeach ?>
        </fieldset>

        <fieldset class='search-task__categories'>
            <legend>Дополнительно</legend>
            <?php foreach ($searchModel::getExtraFieldsList() as $key => $extraField) : ?>
                <input type='checkbox' id='<?= $key ?>' name='extraFields[]<?= $key ?>'
                       class='visually-hidden checkbox__input' value='<?= $key ?>'
                    <?= in_array($key, $loadExtraFields) ? 'checked' : '' ?>>
                <label for='<?= $key ?>'><?= $extraField ?></label>
            <?php endforeach ?>
        </fieldset>

        <label for="searchByName" class="search-task__name">Поиск по имени</label>
        <input type="search" name="search" id="searchByName" placeholder="Поиск" class="input-middle input"
               value="<?= empty($searchModel->search) ? '' : $searchModel->search ?>">
        <button class="button" type="submit">Искать</button>

        <?php $form::end() ?>

    </div>
</section>

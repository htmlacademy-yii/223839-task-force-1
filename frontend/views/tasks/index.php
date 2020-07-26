<?php
/* @var $this yii\web\View
 * @var $tasks Tasks
 * @var $categories Categories
 * @var $searchModel TasksFilterForms
 * @var $pagination Pagination
 */

use frontend\models\Categories;
use frontend\models\forms\TasksFilterForms;
use frontend\models\Tasks;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

$this->title = "TaskForce";
?>

<section class="new-task">
    <div class="new-task__wrapper">
        <h1>Новые задания</h1>
        <?php
        foreach ($tasks as $task) : ?>
            <div class="new-task__card">
                <div class="new-task__title">
                    <h2>
                        <a href="<?= Url::toRoute(['tasks/view', 'id' => $task->id]) ?>">
                            <?= Html::encode($task->title) ?>
                        </a>
                    </h2>
                    <p>
                        <a href="#" class="new-task__type link-regular">
                            <?= $task->category->name ?>
                        </a>
                    </p>
                </div>
                <div class="new-task__icon new-task__icon--<?= $task->category->icon ?>"></div>
                <p class="new-task_description"><?= HTMLPurifier::process($task->description) ?></p>
                <b class="new-task__price new-task__price--<?= $task->category->icon ?>">
                    <?= Html::encode($task->budget) . ' ₽' ?>
                </b>
                <p class='new-task__place'><?= $task->city->name . ' ' . Html::encode($task->address) ?></p>
                <span class="new-task__time"><?= Yii::$app->formatter->asRelativeTime($task->created_at) ?></span>
            </div>
        <?php
        endforeach; ?>

    </div>

    <div class="new-task__pagination">
        <?=
        LinkPager::widget([
            'pagination'       => $pagination,
            'options'          => [
                'class' => 'new-task__pagination-list'
            ],
            'prevPageLabel'    => '',
            'nextPageLabel'    => '',
            'pageCssClass'     => 'pagination__item',
            'prevPageCssClass' => 'pagination__item',
            'nextPageCssClass' => 'pagination__item'
        ])
        ?>
    </div>
</section>

<section class="search-task">
    <div class="search-task__wrapper">
        <?php $form = ActiveForm::begin([
            'method'  => 'get',
            'action'  => ['tasks/search'],
            'options' => [
                'class' => 'search-task__form'
            ]
        ]);

        $loadCategories = empty($searchModel->categories) ? [] : $searchModel->categories;
        $loadExtraFields = empty($searchModel->extraFields) ? [] : $searchModel->extraFields;
        ?>
        <fieldset class='search-task__categories'>
            <legend>Категории</legend>
            <?php foreach ($categories as $key => $category): ?>
                <input type='checkbox' id='category-<?= $key ?>' name='categories[]<?= $key ?>'
                       class='visually-hidden checkbox__input' value='<?= $key ?>'
                    <?= in_array($key, $loadCategories) ? 'checked' : '' ?>>
                <label for='category-<?= $key ?>'><?= $category ?></label>
            <?php endforeach ?>
        </fieldset>

        <fieldset class="search-task__categories">
            <legend>Дополнительно</legend>
            <?php foreach ($searchModel::getExtraFieldsList() as $key => $extraField) : ?>
                <input type='checkbox' id='<?= $key ?>' name='extraFields[]<?= $key ?>'
                       class='visually-hidden checkbox__input' value='<?= $key ?>'
                    <?= in_array($key, $loadExtraFields) ? 'checked' : '' ?>>
                <label for='<?= $key ?>'><?= $extraField ?></label>
            <?php endforeach ?>
        </fieldset>

        <label for=period-selector"" class="search-task__name">Период</label>
        <select name="period" class="multiple-select input" id="period-selector">
            <?php foreach ($searchModel::getPeriodList() as $key => $period) : ?>
                <option value="<?= $key ?>" <?= $key === $searchModel->period ? 'selected' : '' ?>><?= $period ?></option>
            <?php endforeach ?>
        </select>

        <label for="searchByName" class="search-task__name">Поиск по названию</label>
        <input type="search" name="search" id="searchByName" placeholder="Поиск" class="input-middle input"
               value="<?= empty($searchModel->search) ? '' : $searchModel->search ?>">

        <button class="button" type="submit">Искать</button>

        <?php $form::end() ?>
    </div>
</section>

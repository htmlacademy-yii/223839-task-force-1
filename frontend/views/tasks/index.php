<?php
/* @var $this yii\web\View
 * @var $tasks \frontend\models\Tasks
 * @var $categories \frontend\models\Categories
 * @var $filterForm \frontend\models\TasksFilterForms
 */

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\widgets\ActiveForm;

$this->title = "TaskForce";
?>
<section class="new-task">
    <div class="new-task__wrapper">
        <h1>Новые задания</h1>
        <?php foreach ($tasks as $task) : ?>
            <div class="new-task__card">
                <div class="new-task__title">
                    <a href="#" class="link-regular"><h2><?= Html::encode($task->title) ?></h2></a>
                    <a class="new-task__type link-regular" href="#"><p><?= Html::encode($task->category->name) ?></p>
                    </a>
                </div>
                <div class="new-task__icon new-task__icon--<?= $task->category->icon ?>"></div>
                <p class="new-task_description"><?= HTMLPurifier::process($task->description) ?></p>
                <b class="new-task__price new-task__price--<?= $task->category->icon ?>">
                    <?= Html::encode($task->budget) ?> ₽</b>
                <p class="new-task__place"><?= $task->city->city ?>, <?= Html::encode($task->address) ?></p>
                <span class="new-task__time"><?= Yii::$app->formatter->asRelativeTime($task->created_at) ?></span>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="new-task__pagination">
        <ul class="new-task__pagination-list">
            <li class="pagination__item"><a href="#"></a></li>
            <li class="pagination__item pagination__item--current">
                <a>1</a></li>
            <li class="pagination__item"><a href="#">2</a></li>
            <li class="pagination__item"><a href="#">3</a></li>
            <li class="pagination__item"><a href="#"></a></li>
        </ul>
    </div>
</section>

<section class="search-task">
    <div class="search-task__wrapper">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'action' => ['tasks/search'],
            'options' => [
                'class' => 'search-task__form'
            ]
        ]);
        $loadCategories = $filterForm->categories;
        $loadExtraFields = $filterForm->extraFields;
        ?>
        <?= Html::beginTag('fieldset', ['class' => 'search-task__categories']) ?>
        <?= Html::tag('legend', 'Категории') ?>
        <?= $form
            ->field($filterForm, 'categories')
            ->label(false)
            ->checkboxList($categories,
                [
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
                ])
        ?>
        <?= Html::endTag('fieldset') ?>
        <?= Html::beginTag('fieldset', ['class' => 'search-task__categories']) ?>
        <?= Html::tag('legend', 'Дополнительно') ?>
        <?= $form
            ->field($filterForm, 'extraFields')
            ->label(false)
            ->checkboxList($filterForm::getExtraFieldsdList(), [
                'item' => function (
                    int $index,
                    string $label,
                    string $name,
                    bool $checked,
                    string $value
                ) use ($loadExtraFields) : string {
                    $checked = ($checked === true) ? 'checked' : '';
                    $id = "extraFields-{$index}";
                    return "<input type='checkbox' id='{$id}' name='{$name}]'
                    class='visually-hidden checkbox__input' value='{$value}' {$checked}>
                    <label for='{$id}'>{$label}</label>";
                }
            ])
        ?>
        <?= Html::endTag('fieldset') ?>
        <?= $form
            ->field($filterForm, 'period', ['options' => ['tag' => false]])
            ->label('Период', ['class' => 'search-task__name'])
            ->listBox($filterForm::getPeriodList(), ['size' => 1, 'class' => 'multiple-select input']) ?>
        <?= $form
            ->field($filterForm, 'search', ['options' => ['tag' => false]])
            ->input('search', ['class' => 'input-middle input'])
            ->label('Поиск по названию', ['class' => 'search-task__name']) ?>
        <?= Html::submitButton('Искать', ['class' => 'button']) ?>
        <?php $form::end() ?>
    </div>
</section>

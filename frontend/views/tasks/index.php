<?php
/* @var $this yii\web\View
 * @var $tasks \frontend\models\Tasks
 * @var $categories \frontend\models\Categories
 * @var $searchModel \frontend\models\forms\TasksFilterForms
 * @var $pagination \yii\data\Pagination
 */

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\widgets\ActiveForm;

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
                        <?= Html::a(Html::encode($task->title),
                          ['tasks/view', 'id' => $task->id],
                          ['class' => 'link-regular']
                        ) ?>
                    </h2>
                    <p>
                        <?= Html::a(Html::encode($task->category->name),
                          ['#'],
                          ['class' => 'new-task__type link-regular']
                        ) ?>
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
        \yii\widgets\LinkPager::widget([
          'pagination' => $pagination,
          'options' => [
            'class' => 'new-task__pagination-list'
          ],
          'prevPageLabel' => '',
          'nextPageLabel' => '',
          'pageCssClass' => 'pagination__item',
          'prevPageCssClass' => 'pagination__item',
          'nextPageCssClass' => 'pagination__item'
        ])
        ?>
    </div>
</section>

<section class="search-task">
    <div class="search-task__wrapper">
        <?php
        $form = ActiveForm::begin([
          'method' => 'get',
          'action' => ['tasks/search'],
          'options' => [
            'class' => 'search-task__form'
          ]
        ]);

        $loadCategories = $searchModel->categories;
        $loadExtraFields = $searchModel->extraFields;
        ?>
        <fieldset class='search-task__categories'>
            <legend>Категории</legend>
            <?= $form
              ->field($searchModel, 'categories')
              ->label(false)
              ->checkboxList($categories,
                [
                  'item' => function (
                    int $index,
                    string $label,
                    string $name,
                    bool $checked,
                    string $value
                  ) use ($categories) : string {
                      $checked = ($checked === true) ? 'checked' : '';
                      $id = "category-{$value}";

                      return "<input type='checkbox' id='{$id}' name='{$name}'
                    class='visually-hidden checkbox__input' value='{$value}' {$checked}>
                    <label for='{$id}'>{$label}</label>";
                  }
                ])
            ?>
        </fieldset>

        <fieldset class="search-task__categories">
            <legend>Дополнительно</legend>
            <?= $form
              ->field($searchModel, 'extraFields')
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
                    $id = "extraFields-{$value}";
                    return "<input type='checkbox' id='{$id}' name='{$name}]'
                    class='visually-hidden checkbox__input' value='{$value}' {$checked}>
                    <label for='{$id}'>{$label}</label>";
                }
              ])
            ?>
        </fieldset>

        <?= $form
          ->field($searchModel, 'period', ['options' => ['tag' => false]])
          ->label('Период', ['class' => 'search-task__name'])
          ->listBox($searchModel::getPeriodList(), ['size' => 1, 'class' => 'multiple-select input']) ?>
        <?= $form
          ->field($searchModel, 'search', ['options' => ['tag' => false]])
          ->input('search', ['class' => 'input-middle input'])
          ->label('Поиск по названию', ['class' => 'search-task__name']) ?>

        <?= Html::submitButton('Искать', ['class' => 'button']) ?>
        <?php
        $form::end() ?>
    </div>
</section>

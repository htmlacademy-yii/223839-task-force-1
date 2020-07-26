<?php

namespace frontend\models\forms;

use frontend\models\Tasks;
use frontend\services\filters\tasks\CategoriesFilter;
use frontend\services\filters\tasks\ExtraFieldsFilter;
use frontend\services\filters\tasks\PeriodFilter;
use frontend\services\filters\tasks\SearchFilter;
use yii\data\ActiveDataProvider;

class TasksFilterForms extends FilterForm
{
    const
        CREATED_TODAY = 24, // HOUR
        CREATED_WEEK = 7, // DAY
        CREATED_MONTH = 1, // MONTH
        ALL_TIME = 0;

    const
        WITHOUT_RESPONSES = 'withoutResponses',
        REMOTE_WORK = 'remoteWork';

    public        $categories  = '';
    public        $extraFields = '';
    public int    $period      = self::CREATED_WEEK;
    public string $search      = '';

    public static function getPeriodList(): array
    {
        return [
            static::CREATED_TODAY => 'За день',
            static::CREATED_WEEK  => 'За неделю',
            static::CREATED_MONTH => 'За месяц',
            static::ALL_TIME      => 'За все время'
        ];
    }

    public static function getExtraFieldsList(): array
    {
        return [
            static::WITHOUT_RESPONSES => 'Нет откликов',
            static::REMOTE_WORK       => 'Удаленная работа'
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'categories'  => 'Категории',
            'extraFields' => 'Дополнительно',
            'period'      => 'Период',
            'search'      => 'Поиск по названию'
        ];
    }

    public function rules(): array
    {
        return [
            [['categories', 'extraFields', 'period', 'search'], 'safe'],
        ];
    }

    public function search(array $data, int $pageSize = 5): ActiveDataProvider
    {
        $query = Tasks::find()
            ->andWhere(['status' => Tasks::STATUS_NEW])
            ->with(['city', 'category', 'responses'])
            ->orderBy(['created_at' => SORT_DESC]);

        $query = $this->setFilters(
            $query, $data,
            new CategoriesFilter(),
            new ExtraFieldsFilter(),
            new PeriodFilter(),
            new SearchFilter(),
        );

        if ($this->validate()) {
            $this->load([$this->formName() => $data]);
        }

        return new ActiveDataProvider([
            'query'      => $query,
            'pagination' => ['pageSize' => $pageSize]
        ]);
    }
}

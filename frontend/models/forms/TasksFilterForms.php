<?php

namespace frontend\models\forms;

use frontend\DTO\FilterFormDTO;
use frontend\services\filters\tasks\CategoriesFilter;
use frontend\services\filters\tasks\ExtraFieldsFilter;
use frontend\services\filters\tasks\PeriodFilter;
use frontend\services\filters\tasks\SearchFilter;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

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

    public function search(FilterFormDTO $DTO): ActiveDataProvider
    {
        if ($this->validate()) {
            $this->load($DTO->getData());
        }

        $this->setFilters(
            $DTO->getQuery(),
            $this->getFormData($DTO->getData())
        );

        return new ActiveDataProvider([
            'query'      => $DTO->getQuery(),
            'pagination' => ['pageSize' => $DTO->getPageSize()]
        ]);
    }

    private function setFilters(ActiveQuery $query, array $data): void
    {
        $this->setFilter(new CategoriesFilter($query, $data));
        $this->setFilter(new ExtraFieldsFilter($query, $data));
        $this->setFilter(new PeriodFilter($query, $data));
        $this->setFilter(new SearchFilter($query, $data));
    }
}

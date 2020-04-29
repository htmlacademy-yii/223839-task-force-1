<?php

namespace frontend\models;

use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class TasksFilterForms extends Model
{
    const CREATED_TODAY = 1;
    const CREATED_WEEK = 7;
    const CREATED_MONTH = 30;

    const WITHOUT_RESPONSES = 'withoutResponses';
    const REMOTE_WORK = 'remoteWork';

    public $categories = '';
    public $extraFields = '';
    public int $period = self::CREATED_WEEK;
    public string $search = '';

    private array $data = [];
    private ActiveQuery $query;

    public function attributeLabels(): array
    {
        return [
            'categories' => 'Категории',
            'extraFields' => 'Дополнительно',
            'period' => 'Период',
            'search' => 'Поиск по названию'
        ];
    }

    public function rules(): array
    {
        return [
            [['categories', 'extraFields', 'period', 'search'], 'safe'],
        ];
    }

    public static function getPeriodList(): array
    {
        return [
            self::CREATED_TODAY => 'За день',
            self::CREATED_WEEK => 'За неделю',
            self::CREATED_MONTH => 'За месяц'
        ];
    }

    public static function getExtraFieldsdList(): array
    {
        return [
            self::WITHOUT_RESPONSES => 'Нет откликов',
            self::REMOTE_WORK => 'Удаленная работа'
        ];
    }

    public function setData(array $data): void
    {
        $this->data = ArrayHelper::getValue($data, $this->formName());
    }

    public function setFilters(ActiveQuery $tasks): void
    {
        $this->query = $tasks;

        $this->setExtraFieldsFilter();
        $this->setCategoriesFilter();
        $this->setPeriodFilter();
        $this->setSearchFilter();
    }

    private function setExtraFieldsFilter(): void
    {
        if (!empty($this->getExtraFields())) {
            // TODO
            foreach (self::getExtraFieldsdList() as $key => $value) {
                switch ($key) {
                    case $this->isWithoutResponses($key) :
                        $this->setWithoutResponsesFilter();
                        break;
                    case $this->isRemoteWork($key):
                        $this->setRemoteWorkFilter();
                        break;
                }
            }
        }
    }

    private function setWithoutResponsesFilter(): void
    {
        $responses = Responses::find()
            ->select('task_id')
            ->distinct()
            ->andWhere(['IS NOT', 'performer_id', null])
            ->all();

        $tasksWithResponses = [];
        foreach ($responses as $response) {
            $tasksWithResponses[] = ArrayHelper::getValue($response, 'task_id');
        }

        $this->query->andFilterWhere(['NOT IN', 'id', $tasksWithResponses]);
    }

    private function setRemoteWorkFilter(): void
    {
        $this->query->andWhere(['=', 'remoteWork', 1]);
    }


    private function isWithoutResponses($key): bool
    {
        if (ArrayHelper::keyExists($key, $this->getExtraFields()) && $key === self::WITHOUT_RESPONSES) {
            return true;
        }
        return false;
    }

    private function isRemoteWork($key): bool
    {
        if (ArrayHelper::keyExists($key, $this->getExtraFields()) && $key === self::REMOTE_WORK) {
            return true;
        }
        return false;
    }

////////////////////////// EXTRA FIELDS FILTERS //////////////
/////////////////////////////////////////////////////////////

    private function setCategoriesFilter(): void
    {
        $this->query->andFilterWhere(['category_id' => $this->categories]);
    }


    private function setPeriodFilter(): void
    {
        $this->query->andFilterWhere([
            '>',
            'created_at',
            new Expression("CURRENT_TIMESTAMP - INTERVAL {$this->getPeriod()} DAY")
        ]);
    }

    private function setSearchFilter(): void
    {
        if (!empty($this->getSearch())) {
            $this->query->andFilterWhere(['LIKE', 'title', "{$this->getSearch()}"]);
        }
    }

    private function getExtraFields(): array
    {
        $extraFields = ArrayHelper::getValue($this->data, 'extraFields');

        if (empty($extraFields)) {
            return [];
        }

        return array_flip($extraFields);
    }

    private function getSearch(): string
    {
        return ArrayHelper::getValue($this->data, 'search');
    }

    private function getPeriod(): int
    {
        return ArrayHelper::getValue($this->data, 'period');
    }
}

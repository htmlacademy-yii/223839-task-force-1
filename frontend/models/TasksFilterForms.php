<?php

namespace frontend\models;

use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\web\Request;

class TasksFilterForms extends Model
{
    const CREATED_TODAY = 24; // HOUR
    const CREATED_WEEK = 7; // DAY
    const CREATED_MONTH = 1; // MONTH
    const ALL_TIME = 0;

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


    public function search(array $data): ActiveDataProvider
    {
        $this->query = Tasks::find()
            ->andWhere(['status' => Tasks::STATUS_NEW])
            ->with(['city', 'category', 'responses'])
            ->orderBy(['created_at' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $this->query,
            'pagination' => ['pageSize' => 5]
        ]);

        if (!$this->load($data) && $this->validate()) {
            return $dataProvider;
        }

        $this->data = ArrayHelper::getValue($data, $this->formName());
        $this->setFilters();

        return $dataProvider;
    }


    public static function getPeriodList(): array
    {
        return [
            self::CREATED_TODAY => 'За день',
            self::CREATED_WEEK => 'За неделю',
            self::CREATED_MONTH => 'За месяц',
            self::ALL_TIME => 'За все время'
        ];
    }

    public static function getExtraFieldsdList(): array
    {
        return [
            self::WITHOUT_RESPONSES => 'Нет откликов',
            self::REMOTE_WORK => 'Удаленная работа'
        ];
    }

    public function setFilters(): void
    {
        $this->setExtraFieldsFilter();

        $this->setCategoriesFilter();

        $this->setPeriodFilter();

        $this->setSearchFilter();
    }

    private function setExtraFieldsFilter(): void
    {
        if (!empty($this->getExtraFields())) {
            foreach ($this->getExtraFields() as $value) {
                switch ($value) {
                    case self::WITHOUT_RESPONSES:
                        $this->setWithoutResponsesExtraFieldsFilter();
                        break;
                    case self::REMOTE_WORK:
                        $this->setRemoteWorkExtraFieldsFilter();
                        break;
                }
            }
        }
    }

    private function setWithoutResponsesExtraFieldsFilter(): void
    {
        $responses = Responses::find()
            ->select('task_id')
            ->distinct()
            ->andWhere(['IS NOT', 'performer_id', null])
            ->column();

        $this->query->andFilterWhere(['NOT IN', 'id', $responses]);
    }

    private function setRemoteWorkExtraFieldsFilter(): void
    {
        $this->query->andWhere(['remoteWork' => 1]);
    }

    private function setCategoriesFilter(): void
    {
        $this->query->andFilterWhere(['category_id' => $this->categories]);
    }

    private function setPeriodFilter(): void
    {
        switch ($this->getPeriod()) {
            case self::ALL_TIME:
                return;
            case self::CREATED_TODAY:
                $date = 'HOUR';
                break;
            case self::CREATED_WEEK:
                $date = 'DAY';
                break;
            case self::CREATED_MONTH:
                $date = 'MONTH';
                break;
        }

        $this->query->andFilterWhere([
            '>',
            'created_at',
            new Expression("CURRENT_TIMESTAMP - INTERVAL {$this->getPeriod()} {$date}")
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

        return empty($extraFields) ? [] : $extraFields;
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

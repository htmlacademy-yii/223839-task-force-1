<?php

namespace frontend\models\forms;

use frontend\models\Responses;
use frontend\models\Tasks;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

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

        $dataProvider = new ActiveDataProvider(['query' => $this->query, 'pagination' => ['pageSize' => 5]]);

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
        $extraFields = $this->getExtraFields();

        if (!empty($extraFields)) {
            $extraFieldsFilters = [
              self::WITHOUT_RESPONSES => [$this, 'setWithoutResponsesExtraFieldsFilter'],
              self::REMOTE_WORK => [$this, 'setRemoteWorkExtraFieldsFilter'],
            ];

            foreach ($extraFields as $extraField) {
                if (ArrayHelper::keyExists($extraField, $extraFieldsFilters)) {
                    call_user_func($extraFieldsFilters[$extraField]);
                }
            }
        }
    }

    private function setWithoutResponsesExtraFieldsFilter(): void
    {
        $tasksWithoutResponse = Responses::find()
          ->distinct()
          ->select('task_id')
          ->andWhere(['IS NOT', 'performer_id', null])
          ->column();

        $this->query->andFilterWhere(['NOT IN', 'id', $tasksWithoutResponse]);
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
        $period = (int)ArrayHelper::getValue($this->data, 'period');

        if ($period === self::ALL_TIME) {
            return;
        }

        $periods = [
          self::CREATED_TODAY => 'HOUR',
          self::CREATED_WEEK => 'DAY',
          self::CREATED_MONTH => 'MONTH'
        ];

        $date = ArrayHelper::keyExists($period, $periods) ? $periods[$period] : 'MONTH';

        $this->query->andFilterWhere([
          '>',
          'created_at',
          new Expression("CURRENT_TIMESTAMP - INTERVAL {$period} {$date}")
        ]);
    }

    private function setSearchFilter(): void
    {
        $search = (string)ArrayHelper::getValue($this->data, 'search');

        if (!empty($search)) {
            $this->query->andFilterWhere(['LIKE', 'title', $search]);
        }
    }

    private function getExtraFields(): array
    {
        $extraFields = ArrayHelper::getValue($this->data, 'extraFields');

        return empty($extraFields) ? [] : $extraFields;
    }
}

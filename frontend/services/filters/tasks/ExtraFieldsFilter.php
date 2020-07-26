<?php

namespace frontend\services\filters\tasks;

use frontend\models\forms\TasksFilterForms as Form;
use frontend\models\Tasks;
use frontend\services\filters\Filter;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class ExtraFieldsFilter implements Filter
{

    public function setFilter(ActiveQuery $query, array $data): ActiveQuery
    {
        $extraFields = $this->getExtraFields($data);

        if (isset($data['extraFields']) && !empty($extraFields)) {
            $extraFieldsFilters = [
                Form::WITHOUT_RESPONSES => [$this, 'setWithoutResponses'],
                Form::REMOTE_WORK       => [$this, 'setRemoteWork'],
            ];

            foreach ($extraFields as $extraField) {
                if (ArrayHelper::keyExists($extraField, $extraFieldsFilters)) {
                    call_user_func($extraFieldsFilters[$extraField], $query);
                }
            }
        }

        return $query;
    }

    private function setWithoutResponses(ActiveQuery $query): void
    {
        $tasksWithoutResponses = Tasks::getTasksResponses()->distinct()->select('task_id')->column();

        $query->andFilterWhere(['NOT IN', 'id', $tasksWithoutResponses]);
    }

    private function setRemoteWork(ActiveQuery $query): void
    {
        $query->andWhere(['remoteWork' => 1]);
    }

    private function getExtraFields(array $data): array
    {
        $extraFields = ArrayHelper::getValue($data, 'extraFields');

        return empty($extraFields)
            ? []
            : $extraFields;
    }
}
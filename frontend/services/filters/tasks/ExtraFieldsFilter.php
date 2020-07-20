<?php

namespace frontend\services\filters\tasks;

use frontend\models\forms\TasksFilterForms as Form;
use frontend\models\Tasks;
use frontend\services\filters\Filter;
use yii\helpers\ArrayHelper;

class ExtraFieldsFilter extends Filter
{
    public function execute(): void
    {
        $extraFields = $this->getExtraFields();

        if (isset($this->data['extraFields']) && !empty($extraFields)) {
            $extraFieldsFilters = [
                Form::WITHOUT_RESPONSES => [$this, 'setWithoutResponses'],
                Form::REMOTE_WORK       => [$this, 'setRemoteWork'],
            ];

            foreach ($extraFields as $extraField) {
                if (ArrayHelper::keyExists($extraField, $extraFieldsFilters)) {
                    call_user_func($extraFieldsFilters[$extraField], $this->query);
                }
            }
        }
    }

    private function setWithoutResponses(): void
    {
        $tasksWithoutResponses = Tasks::getTasksResponses()->distinct()->select('task_id')->column();

        $this->query->andFilterWhere(['NOT IN', 'id', $tasksWithoutResponses]);
    }

    private function setRemoteWork(): void
    {
        $this->query->andWhere(['remoteWork' => 1]);
    }

    private function getExtraFields(): array
    {
        $extraFields = ArrayHelper::getValue($this->data, 'extraFields');

        return empty($extraFields)
            ? []
            : $extraFields;
    }
}
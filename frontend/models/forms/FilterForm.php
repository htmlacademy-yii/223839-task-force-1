<?php


namespace frontend\models\forms;

use frontend\services\filters\Filter;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class FilterForm extends Model
{
    protected function getFormData(array $data): array
    {
        $data = ArrayHelper::getValue($data, $this->formName());

        return is_null($data)
            ? []
            : $data;
    }

    protected function setFilter(Filter $filter): void
    {
        $filter->execute();
    }
}
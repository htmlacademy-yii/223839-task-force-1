<?php

namespace frontend\services\filters\tasks;

use frontend\services\filters\Filter;
use yii\helpers\ArrayHelper;

class SearchFilter extends Filter
{
    public function execute(): void
    {
        $search = (string)ArrayHelper::getValue($this->data, 'search');

        if (isset($this->data['search']) && !empty($search)) {
            $this->query->andFilterWhere(['LIKE', 'title', $search]);
        }
    }
}
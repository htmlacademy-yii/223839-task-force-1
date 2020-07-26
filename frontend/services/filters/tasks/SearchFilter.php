<?php

namespace frontend\services\filters\tasks;

use frontend\services\filters\Filter;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class SearchFilter implements Filter
{
    public function setFilter(ActiveQuery $query, array $data): ActiveQuery
    {
        $search = (string)ArrayHelper::getValue($data, 'search');

        if (isset($this->data['search']) && !empty($search)) {
            $query->andFilterWhere(['LIKE', 'title', $search]);
        }

        return $query;
    }
}
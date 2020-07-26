<?php

namespace frontend\services\filters\tasks;

use frontend\services\filters\Filter;
use yii\db\ActiveQuery;

class CategoriesFilter implements Filter
{
    public function setFilter(ActiveQuery $query, array $data): ActiveQuery
    {
        if (isset($data['categories'])) {
            $categories = $data['categories'];

            if (!empty($categories)) {
                $query->andFilterWhere(['category_id' => $categories]);
            }
        }

        return $query;
    }
}
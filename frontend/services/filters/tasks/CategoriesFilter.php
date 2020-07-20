<?php

namespace frontend\services\filters\tasks;

use frontend\services\filters\Filter;

class CategoriesFilter extends Filter
{
    public function execute(): void
    {
        if (isset($this->data['categories'])) {
            $categories = $this->data['categories'];

            if (!empty($categories)) {
                $this->query->andFilterWhere(['category_id' => $categories]);
            }
        }
    }
}
<?php

namespace frontend\services\filters\users\filters;

use frontend\models\UsersSpecializations;
use frontend\services\filters\Filter;
use yii\db\ActiveQuery;

class CategoriesFilter implements Filter
{

    public function setFilter(ActiveQuery $query, array $data): ActiveQuery
    {
        if (isset($data['categories'])) {
            $categories = $data['categories'];

            if (!empty($categories)) {
                $users = $this->getUsersWithCategory($categories);
                $query->andFilterWhere([
                    'id' => $users
                ]);
            }
        }

        return $query;
    }

    private function getUsersWithCategory(array $categories)
    {
        $users = UsersSpecializations::getPerformersInCategories($categories);

        return empty($users)
            ? 0
            : $users;
    }
}
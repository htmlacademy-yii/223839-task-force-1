<?php

namespace frontend\services\filters\users;

use frontend\models\UsersSpecializations;
use frontend\services\filters\Filter;

class CategoriesFilter extends Filter
{

    public function execute(): void
    {
        if (isset($this->data['categories'])) {
            $categories = $this->data['categories'];

            if (!empty($categories)) {
                $users = $this->getUsersWithCategory($categories);
                $this->query->andFilterWhere([
                    'id' => $users
                ]);
            }
        }
    }

    private function getUsersWithCategory(array $categories)
    {
        $users = UsersSpecializations::getPerformersInCategories($categories);

        return empty($users)
            ? 0
            : $users;
    }
}
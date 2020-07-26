<?php

namespace frontend\services\filters\users\sorts;

use frontend\services\filters\Filter;
use yii\db\ActiveQuery;

class CountOrdersSort implements Filter
{
    public function setFilter(ActiveQuery $query, array $data): ActiveQuery
    {
        return $query->addSelect(['COUNT(tasks.performer_id) AS tasks_counter'])
            ->joinWith('tasksPerformer', false)
            ->groupBy('users.id')
            ->orderBy(['tasks_counter' => SORT_DESC]);
    }
}
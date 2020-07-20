<?php

namespace frontend\services\filters\users\sorts;

use frontend\models\forms\UsersFiltersForm as Form;
use frontend\services\filters\Sort;

class CountOrdersSort extends Sort
{
    public function execute(): string
    {
        $this->query->addSelect(['COUNT(tasks.performer_id) AS tasks_counter'])
            ->joinWith('tasksPerformer', false)
            ->groupBy('users.id')
            ->orderBy(['tasks_counter' => SORT_DESC]);

        return Form::SORT_COUNT_ORDERS;
    }
}
<?php

namespace frontend\services\filters\users\sorts;

use frontend\models\forms\UsersFiltersForm as Form;
use frontend\services\filters\Sort;

class LastActivitySort extends Sort
{
    public function execute(): string
    {
        $this->query->orderBy(['last_activity' => SORT_DESC]);

        return Form::SORT_LAST_ACTIVITY;
    }
}
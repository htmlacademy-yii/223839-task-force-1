<?php

namespace frontend\services\filters\users\sorts;

use frontend\models\forms\UsersFiltersForm as Form;
use frontend\services\filters\Sort;

class PopularSort extends Sort
{

    public function execute(): string
    {
        $this->query->orderBy(['visit_counter' => SORT_DESC]);

        return Form::SORT_POPULAR;
    }
}
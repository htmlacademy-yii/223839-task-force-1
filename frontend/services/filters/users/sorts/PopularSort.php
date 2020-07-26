<?php

namespace frontend\services\filters\users\sorts;

use frontend\services\filters\Filter;
use yii\db\ActiveQuery;

class PopularSort implements Filter
{
    public function setFilter(ActiveQuery $query, array $data): ActiveQuery
    {
        return $query->orderBy(['visit_counter' => SORT_DESC]);
    }
}
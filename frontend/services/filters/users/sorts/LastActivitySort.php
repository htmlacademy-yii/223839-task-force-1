<?php

namespace frontend\services\filters\users\sorts;

use frontend\services\filters\Filter;
use yii\db\ActiveQuery;

class LastActivitySort implements Filter
{
    public function setFilter(ActiveQuery $query, array $data): ActiveQuery
    {
        return $query->orderBy(['last_activity' => SORT_DESC]);
    }
}
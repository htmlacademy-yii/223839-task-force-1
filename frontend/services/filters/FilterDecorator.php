<?php

namespace frontend\services\filters;

use yii\db\ActiveQuery;

class FilterDecorator implements Filter
{
    private Filter $filter;

    public function __construct(Filter $filter)
    {
        $this->filter = $filter;
    }

    public function setFilter(ActiveQuery $query, array $data): ActiveQuery
    {
        return $this->filter->setFilter($query, $data);
    }
}
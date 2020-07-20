<?php

namespace frontend\services\filters;

use yii\db\ActiveQuery;

abstract class Sort
{
    protected ActiveQuery $query;

    public function __construct(ActiveQuery $query)
    {
        $this->query = $query;
    }

    abstract public function execute(): string;
}
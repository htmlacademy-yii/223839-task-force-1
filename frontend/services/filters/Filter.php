<?php

namespace frontend\services\filters;

use yii\db\ActiveQuery;

abstract class Filter
{
    protected ActiveQuery $query;
    protected array       $data;

    public function __construct(ActiveQuery $query, array $data)
    {
        $this->query = $query;
        $this->data = $data;
    }

    abstract public function execute(): void;
}
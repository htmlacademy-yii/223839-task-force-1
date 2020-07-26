<?php

namespace frontend\services\filters;

use yii\db\ActiveQuery;

interface Filter
{
    public function setFilter(ActiveQuery $query, array $data): ActiveQuery;
}
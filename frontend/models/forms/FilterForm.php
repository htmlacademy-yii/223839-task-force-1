<?php

namespace frontend\models\forms;

use frontend\services\filters\Filter;
use frontend\services\filters\FilterDecorator;
use yii\base\Model;
use yii\db\ActiveQuery;

abstract class FilterForm extends Model
{
    protected function setFilters(ActiveQuery $query, array $data, Filter...$filters): ActiveQuery
    {
        foreach ([...$filters] as $filter) {
            $query = (new FilterDecorator($filter))->setFilter($query, $data);
        }

        return $query;
    }
}
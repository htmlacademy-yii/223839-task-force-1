<?php

namespace frontend\services\filters\tasks;

use frontend\models\forms\TasksFilterForms as Form;
use frontend\services\filters\Filter;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class PeriodFilter implements Filter
{

    public function setFilter(ActiveQuery $query, array $data): ActiveQuery
    {
        $period = (int)ArrayHelper::getValue($data, 'period');

        if ($period === Form::ALL_TIME) {
            return $query;
        }

        $periods = [
            Form::CREATED_TODAY => 'HOUR',
            Form::CREATED_WEEK  => 'DAY',
            Form::CREATED_MONTH => 'MONTH',
        ];

        $date = key_exists($period, $periods) ? $periods[$period] : 'MONTH';

        return $query->andFilterWhere([
            '>',
            'created_at',
            new Expression("CURRENT_TIMESTAMP - INTERVAL {$period} {$date}")
        ]);
    }
}
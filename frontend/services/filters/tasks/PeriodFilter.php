<?php

namespace frontend\services\filters\tasks;

use frontend\models\forms\TasksFilterForms as Form;
use frontend\services\filters\Filter;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class PeriodFilter extends Filter
{

    public function execute(): void
    {
        if (($period = (int)ArrayHelper::getValue($this->data, 'period')) === Form::ALL_TIME) {
            return;
        }

        $periods = [
            Form::CREATED_TODAY => 'HOUR',
            Form::CREATED_WEEK  => 'DAY',
            Form::CREATED_MONTH => 'MONTH'
        ];

        $date = ArrayHelper::keyExists($period, $periods) ? $periods[$period] : 'MONTH';

        $this->query->andFilterWhere([
            '>',
            'created_at',
            new Expression("CURRENT_TIMESTAMP - INTERVAL {$period} {$date}")
        ]);
    }
}
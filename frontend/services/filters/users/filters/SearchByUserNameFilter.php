<?php

namespace frontend\services\filters\users\filters;

use frontend\models\Users;
use frontend\services\filters\Filter;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class SearchByUserNameFilter implements Filter
{
    public function setFilter(ActiveQuery $query, array $data): ActiveQuery
    {
        $search = (string)ArrayHelper::getValue($data, 'search');

        if (isset($data['search']) && !empty($search)) {
            $query->where(['id' => Users::findByUserName($search)->column()]);
        }

        return $query;
    }
}
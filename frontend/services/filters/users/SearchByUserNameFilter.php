<?php

namespace frontend\services\filters\users;

use frontend\models\Users;
use frontend\services\filters\Filter;
use yii\helpers\ArrayHelper;

class SearchByUserNameFilter extends Filter
{
    public function execute(): void
    {
        $search = (string)ArrayHelper::getValue($this->data, 'search');

        if (isset($this->data['search']) && !empty($search)) {
            $this->query->where(['id' => Users::findByUserName($search)->column()]);
        }
    }
}
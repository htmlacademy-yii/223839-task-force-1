<?php

namespace frontend\services\filters\users\sorts;

use frontend\services\filters\Filter;
use yii\db\ActiveQuery;

class RatingSort implements Filter
{
    public function setFilter(ActiveQuery $query, array $data): ActiveQuery
    {
        return $query->addSelect(['AVG(reviews.rating) AS rating'])
            ->joinWith('reviewsPerformer', false)
            ->groupBy('users.id')
            ->orderBy(['rating' => SORT_DESC]);
    }
}
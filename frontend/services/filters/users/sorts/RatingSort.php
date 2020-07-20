<?php

namespace frontend\services\filters\users\sorts;

use frontend\models\forms\UsersFiltersForm as Form;
use frontend\services\filters\Sort;

class RatingSort extends Sort
{
    public function execute(): string
    {
        $this->query->addSelect(['AVG(reviews.rating) AS rating'])
            ->joinWith('reviewsPerformer', false)
            ->groupBy('users.id')
            ->orderBy(['rating' => SORT_DESC]);

        return Form::SORT_RATING;
    }
}
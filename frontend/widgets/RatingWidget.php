<?php

namespace frontend\widgets;

use yii\base\Widget;

class RatingWidget extends Widget
{
    public $rating = 5;

    public function run()
    {
        $rating = $this->rating;
        return $this->render('ratingWidget', compact('rating'));
    }
}

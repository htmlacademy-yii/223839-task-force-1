<?php

namespace frontend\tests\fixtures;

use frontend\models\Categories;
use yii\test\ActiveFixture;

class CategoriesFixtures extends ActiveFixture
{
    public $modelClass = Categories::class;
    public $dataFile = '@frontend/tests/_data/categories.php';
}

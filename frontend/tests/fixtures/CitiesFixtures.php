<?php

namespace frontend\tests\fixtures;

use frontend\models\Cities;
use yii\test\ActiveFixture;

class CitiesFixtures extends ActiveFixture
{
    public $modelClass = Cities::class;
    public $dataFile = '@frontend/tests/_data/cities.php';
}

<?php

namespace frontend\tests\fixtures;

use frontend\models\Tasks;
use yii\test\ActiveFixture;

class TasksFixtures extends ActiveFixture
{
    public $modelClass = Tasks::class;
    public $dataFile = '@frontend/tests/_data/tasks.php';
}

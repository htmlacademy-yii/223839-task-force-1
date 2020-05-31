<?php

namespace frontend\tests\fixtures;

use frontend\models\Users;
use yii\test\ActiveFixture;

class UsersFixtures extends ActiveFixture
{
    public $modelClass = Users::class;
    public $dataFile = '@frontend/tests/_data/Users.php';
}

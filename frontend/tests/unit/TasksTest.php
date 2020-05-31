<?php
namespace frontend\tests;

use frontend\models\Tasks;
use frontend\tests\fixtures\CategoriesFixtures;
use frontend\tests\fixtures\CitiesFixtures;
use frontend\tests\fixtures\TasksFixtures;
use frontend\tests\fixtures\UsersFixtures;

class TasksTest extends \Codeception\Test\Unit
{
    /**
     * @var \frontend\tests\UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->tester->haveFixtures([
          'users' => ['class' => UsersFixtures::class],
          'categories' => ['class' => CategoriesFixtures::class],
          'cities' => ['class' => CitiesFixtures::class],
          'tasks' => ['class' => TasksFixtures::class]
        ]);
    }

    protected function _after()
    {
    }

    // tests
    public function testSaveTask()
    {
        $task = $this->make(Tasks::class,
          [
            'id' => 15,
            'title' => 'title',
            'budget' => 1000,
            'created_at' => \Yii::$app->formatter->asDate('now', 'php:Y-m-d'),
            'city_id' => 1,
            'description' => 'test',
            'category_id' => 1,
            'author_id' => 2,
            'status' => Tasks::STATUS_NEW,
          ]);

        $this->assertTrue($task->validate());
        $this->assertTrue($task->save());
    }
}

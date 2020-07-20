<?php

namespace frontend\tests;

use Codeception\Test\Unit;
use frontend\models\forms\LoginForm;
use frontend\tests\fixtures\CategoriesFixtures;
use frontend\tests\fixtures\CitiesFixtures;
use frontend\tests\fixtures\TasksFixtures;
use frontend\tests\fixtures\UsersFixtures;
use Yii;

class LoginFormTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->tester->haveFixtures([
            'users'      => ['class' => UsersFixtures::class],
            'categories' => ['class' => CategoriesFixtures::class],
            'cities'     => ['class' => CitiesFixtures::class],
            'tasks'      => ['class' => TasksFixtures::class]
        ]);
    }

    protected function _after()
    {
    }

    // tests
    public function testRightLogin()
    {
        $model = $this->make(LoginForm::class, [
            'email'    => 'victoria.fix@mail.ru',
            'password' => 'dpkjvfqvtyz'
        ]);

        $this->assertTrue($model->validate());

        $user = $model->getUser();
        Yii::$app->user->login($user);

        $this->assertFalse(Yii::$app->user->isGuest);
        $this->assertSame('Виктория', Yii::$app->user->identity->first_name);
        $this->assertSame('Соловьева', Yii::$app->user->identity->last_name);
    }

    /**
     * @dataProvider getWrongVariantsLogin
     */
    public function testLoginWrong($password, $name)
    {
        $model = $this->make(LoginForm::class, [
            'email'    => $name,
            'password' => $password
        ]);

        $this->assertFalse($model->validate());
        $this->isNull($model->getUser());
        $this->assertTrue(Yii::$app->user->isGuest);
    }

    public function getWrongVariantsLogin()
    {
        return [
            ['password', 'билли.миллиган@почта.рус'],
            ['BillLading', 'bill.lading@usa.com'],
            ['UsaLading', 'usa@lading.biz'],
        ];
    }
}

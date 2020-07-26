<?php

namespace frontend\tests;

use Codeception\Test\Unit;
use frontend\models\Users;
use frontend\tests\fixtures\CategoriesFixtures;
use frontend\tests\fixtures\CitiesFixtures;
use frontend\tests\fixtures\TasksFixtures;
use frontend\tests\fixtures\UsersFixtures;

class UsersTest extends Unit
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
    public function testUserFindByUserName()
    {
        $username = 'Виктория Соловьева';

        $user = (Users::findByUserName($username)->asArray()->all());

        $this->assertSame('Виктория', $user[0]['first_name']);
        $this->assertSame('Соловьева', $user[0]['last_name']);
    }

    public function testGetUserFullName()
    {
        $user = Users::findOne($this->tester->grabFixture('users')[0]['id']);

        $this->assertSame("{$user->first_name} {$user->last_name}", $user->getFullName());
    }

    public function testSaveCustomer()
    {
        $user = $this->make(Users::class,
            [
                'id'          => 15,
                'first_name'  => 'bill',
                'last_name'   => 'lading',
                'city_id'     => 1,
                'password'    => 'test123213',
                'role'        => Users::ROLE_CUSTOMER,
                'email'       => 'test@customer.email',
            ]);

        $this->assertTrue($user->validate());
        $this->assertTrue($user->save());
    }

    public function testSavePerformer()
    {
        $user = $this->make(Users::class,
            [
                'id'          => 15,
                'first_name'  => 'bill',
                'last_name'   => 'lading',
                'city_id'     => 1,
                'password'    => 'test123213',
                'role'        => Users::ROLE_PERFORMER,
                'email'       => 'test@customer.email',
            ]);

        $this->assertTrue($user->validate());
        $this->assertTrue($user->save());
    }
}

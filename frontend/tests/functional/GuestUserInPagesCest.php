<?php

namespace frontend\tests\functional;

use frontend\tests\fixtures\CategoriesFixtures;
use frontend\tests\fixtures\CitiesFixtures;
use frontend\tests\fixtures\TasksFixtures;
use frontend\tests\fixtures\UsersFixtures;
use frontend\tests\FunctionalTester;

class GuestUserInPagesCest
{
    public function _before(FunctionalTester $I)
    {
        $I->haveFixtures([
          'users'      => ['class' => UsersFixtures::class,],
          'categories' => ['class' => CategoriesFixtures::class],
          'cities'     => ['class' => CitiesFixtures::class],
          'tasks'      => ['class' => TasksFixtures::class]
        ]);
    }

    public function NotOpenTasksPage(FunctionalTester $I)
    {
        $I->amOnPage('/tasks');
        $I->seeInCurrentUrl('/');
        $I->seeResponseCodeIs(200);
        $I->see('Работа там, где ты!');
        $I->dontSeeInCurrentUrl('/tasks');
    }

    public function NotOpenUsersPage(FunctionalTester $I)
    {
        $I->amOnPage('/users');
        $I->seeInCurrentUrl('/');
        $I->seeResponseCodeIs(200);
        $I->see('Работа там, где ты!');
        $I->dontSeeInCurrentUrl('/users');
    }

    public function NotOpenLogoutPage(FunctionalTester $I)
    {
        $I->amOnPage('/logout');
        $I->seeInCurrentUrl('/');
        $I->seeResponseCodeIs(200);
        $I->see('Работа там, где ты!');
        $I->dontSeeInCurrentUrl('/logout');
    }

    public function NotOpenLoginPage(FunctionalTester $I)
    {
        $I->amOnPage('/login');
        $I->seeInCurrentUrl('/');
        $I->seeResponseCodeIs(200);
        $I->see('Работа там, где ты!');
        $I->dontSeeInCurrentUrl('/login');
    }

    public function openRegisterPage(FunctionalTester $I)
    {
        $I->amOnPage(['/register']);
        $I->seeInCurrentUrl('/register');
        $I->seeResponseCodeIs(200);
        $I->see('Регистрация аккаунта');
    }

    public function openMainPage(FunctionalTester $I)
    {
        $I->amOnPage(['/']);
        $I->seeInCurrentUrl('/');
        $I->seeResponseCodeIs(200);
    }
}

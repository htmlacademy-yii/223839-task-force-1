<?php

namespace frontend\tests\functional;

use frontend\models\Users;
use frontend\tests\fixtures\CategoriesFixtures;
use frontend\tests\fixtures\CitiesFixtures;
use frontend\tests\fixtures\TasksFixtures;
use frontend\tests\fixtures\UsersFixtures;
use frontend\tests\FunctionalTester;

class AuthUserInPagesCest
{
    public function _before(FunctionalTester $I)
    {
        $I->haveFixtures([
          'users'      => ['class' => UsersFixtures::class,],
          'categories' => ['class' => CategoriesFixtures::class],
          'cities'     => ['class' => CitiesFixtures::class],
          'tasks'      => ['class' => TasksFixtures::class]
        ]);
        $this->login($I);
    }

    public function login(FunctionalTester $I)
    {
        $user = Users::findOne(['email' => 'victoria.fix@mail.ru']);
        \Yii::$app->user->login($user);

        $I->amOnPage('/');
        $I->seeInCurrentUrl('/tasks');
        $I->see('Новые задания');
    }

    public function OpenTasksPage(FunctionalTester $I)
    {
        $I->amOnPage('/tasks');
        $I->seeInCurrentUrl('/tasks');
        $I->seeResponseCodeIs(200);
        $I->see('Новые задания');
    }

    public function OpenUsersPage(FunctionalTester $I)
    {
        $I->amOnPage('/users');
        $I->seeInCurrentUrl('/users');
        $I->seeResponseCodeIs(200);
        $I->see('Исполнители');
    }

    public function RedirectOpenLogoutPage(FunctionalTester $I)
    {
        $I->amOnPage('/logout');
        $I->seeInCurrentUrl('/');
        $I->seeResponseCodeIs(200);
        $I->see('Работа там, где ты!');
        $I->dontSeeInCurrentUrl('/logout');
    }

    public function RedirectOpenLoginPage(FunctionalTester $I)
    {
        $I->amOnPage('/login');
        $I->seeInCurrentUrl('/tasks');
        $I->seeResponseCodeIs(200);
        $I->see('Новые задания');
        $I->dontSeeInCurrentUrl('/login');
    }

    public function RedirectOpenRegisterPage(FunctionalTester $I)
    {
        $I->amOnPage(['/register']);
        $I->seeInCurrentUrl('/tasks');
        $I->seeResponseCodeIs(200);
        $I->see('Новые задания');
        $I->dontSeeInCurrentUrl('/register');
    }

    public function RedirectopenMainPage(FunctionalTester $I)
    {
        $I->amOnPage(['/']);
        $I->seeInCurrentUrl('/tasks');
        $I->seeResponseCodeIs(200);
        $I->see('Новые задания');
    }
}

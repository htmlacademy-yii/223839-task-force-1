<?php

namespace frontend\tests\functional;

use frontend\tests\fixtures\CategoriesFixtures;
use frontend\tests\fixtures\CitiesFixtures;
use frontend\tests\fixtures\TasksFixtures;
use frontend\tests\fixtures\UsersFixtures;
use frontend\tests\FunctionalTester;

class SignUpFormCest
{
    public function _before(FunctionalTester $I)
    {
        $I->haveFixtures([
          'users'      => ['class' => UsersFixtures::class,],
          'categories' => ['class' => CategoriesFixtures::class],
          'cities'     => ['class' => CitiesFixtures::class],
          'tasks'      => ['class' => TasksFixtures::class]
        ]);
        $this->openRegisterPage($I);
    }

    public function openRegisterPage(FunctionalTester $I)
    {
        $I->amOnPage(['/register']);
        $I->seeInCurrentUrl('/register');
        $I->seeResponseCodeIs(200);
        $I->see('Регистрация аккаунта');
    }

    // tests
    public function registerUserRight(FunctionalTester $I)
    {
        $I->submitForm('form.registration__user-form', [
          'SignupForm[email]'     => 'bill.lading@usa.com',
          'SignupForm[user_name]' => 'Bill Lading',
          'SignupForm[city_id]'   => 1,
          'SignupForm[password]'  => 'dpkjvfqvtyz'
        ]);
        $I->dontSee('error has occurred, try again');
        $I->seeInCurrentUrl('/');
        $I->see('You have successfully registered');
    }

    public function registerUserWrongUserName(FunctionalTester $I)
    {
        $I->submitForm('form.registration__user-form', [
          'SignupForm[email]'     => 'bill.lading@usa.com',
          'SignupForm[user_name]' => 'Bill Of Lading',
          'SignupForm[city_id]'   => 1,
          'SignupForm[password]'  => 'dpkjvfqvtyz'
        ]);
        $I->seeInCurrentUrl('/register');
        $I->see('Имя должно соответствовать шаблону Иван Иванов.
         Нельзя использовать цифры и специальные символы.', '.help-block');
        $I->see('error has occurred, try again');
        $I->amOnPage('/');
        $I->dontSee('You have successfully registered');
    }

    public function registerUserWrongEmail(FunctionalTester $I)
    {
        $I->submitForm('form.registration__user-form', [
          'SignupForm[email]'     => 'bill.lading@21312.com',
          'SignupForm[user_name]' => 'Bill Lading',
          'SignupForm[city_id]'   => 1,
          'SignupForm[password]'  => 'dpkjvfqvtyz'
        ]);
        $I->seeInCurrentUrl('/register');
        $I->see('Значение «email» неверно', '.help-block');
        $I->see('error has occurred, try again');
        $I->amOnPage('/');
        $I->dontSee('You have successfully registered');
    }


    public function submitEmptyForm(FunctionalTester $I)
    {
        $I->submitForm('form.registration__user-form', []);
        $I->seeInCurrentUrl('/register');
        $I->see('Поле обязательно для заполнения', '.field-signupform-email.input-danger .help-block');
        $I->see('Поле обязательно для заполнения', '.field-signupform-user_name.input-danger .help-block');
        $I->see('', '.field-signupform-city_id .help-block');
        $I->see('Поле обязательно для заполнения', '.field-signupform-password.input-danger .help-block');
        $I->see('error has occurred, try again');
    }
}

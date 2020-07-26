<?php

namespace frontend\tests\functional;

use frontend\models\forms\TasksFilterForms;
use frontend\models\Users;
use frontend\tests\fixtures\CategoriesFixtures;
use frontend\tests\fixtures\CitiesFixtures;
use frontend\tests\fixtures\TasksFixtures;
use frontend\tests\fixtures\UsersFixtures;
use frontend\tests\FunctionalTester;

class AuthUserInTasksPageCest
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
        $this->openUsersPage($I);
    }

    public function login(FunctionalTester $I)
    {
        $user = Users::findOne(['email' => 'victoria.fix@mail.ru']);
        \Yii::$app->user->login($user);

        $I->amOnPage('/');
        $I->see('Новые задания');
    }

    public function openUsersPage(FunctionalTester $I)
    {
        $I->amOnPage('/tasks');
        $I->seeInCurrentUrl('/tasks');
        $I->seeResponseCodeIs(200);
        $I->see('Новые задания');
    }

    public function pagination(FunctionalTester $I)
    {
        $I->seeElement('.new-task__pagination');
        $I->click('.pagination__item a[data-page=1]');
        $I->seeInCurrentUrl('page=2');
    }

    public function hasFilterForm(FunctionalTester $I)
    {
        $I->seeElement('form.search-task__form');
        $I->see('Категории', 'form.search-task__form');
        $I->see('Дополнительно', 'form.search-task__form');
        $I->seeElement('input', [
          'value' => TasksFilterForms::WITHOUT_RESPONSES
        ]);
        $I->seeElement('input', [
          'value' => TasksFilterForms::REMOTE_WORK
        ]);
        $I->seeElement('select', ['name' => 'period']);
        $I->seeElement('input', ['name' => 'search']);
    }

    public function setWithoutResponsesExtraFieldFilter(FunctionalTester $I)
    {
        $I->submitForm('form.search-task__form', [
          'extraFields' => [TasksFilterForms::WITHOUT_RESPONSES]
        ]);
        $I->seeInCurrentUrl('/tasks');

        $I->seeCheckboxIsChecked('#' . TasksFilterForms::WITHOUT_RESPONSES);
        $I->dontSeeCheckboxIsChecked('#' . TasksFilterForms::REMOTE_WORK);
    }

    public function setRemoteWorkExtraFieldFilter(FunctionalTester $I)
    {
        $I->submitForm('form.search-task__form', [
            'extraFields' => [TasksFilterForms::REMOTE_WORK]
        ]);
        $I->seeInCurrentUrl('/tasks');

        $I->dontSeeCheckboxIsChecked('#' . TasksFilterForms::WITHOUT_RESPONSES);
        $I->seeCheckboxIsChecked('#' . TasksFilterForms::REMOTE_WORK);
    }

    public function setSearchFilter(FunctionalTester $I)
    {
        $I->submitForm('form.search-task__form', [
          'search' => 'search'
        ]);
        $I->seeInCurrentUrl('/tasks');

        $I->seeInField('#searchByName', 'search');
    }

    public function setPeriodFilter(FunctionalTester $I)
    {
        $I->submitForm('form.search-task__form', [
          'period' => TasksFilterForms::ALL_TIME
        ]);
        $I->seeOptionIsSelected('#period-selector', 'За все время');

        $I->submitForm('form.search-task__form', [
          'period' => TasksFilterForms::CREATED_TODAY
        ]);
        $I->seeOptionIsSelected('#period-selector', 'За день');

        $I->submitForm('form.search-task__form', [
          'period' => TasksFilterForms::CREATED_MONTH
        ]);
        $I->seeOptionIsSelected('#period-selector', 'За месяц');
    }

    public function setCategoriesFilter(FunctionalTester $I)
    {
        $I->submitForm('form.search-task__form', [
          'categories' => [1, 2]
        ]);

        $I->seeCheckboxIsChecked('#category-1');
        $I->seeCheckboxIsChecked('#category-2');
        $I->dontSeeCheckboxIsChecked('#category-3');
        $I->dontSeeCheckboxIsChecked('#category-4');
    }
}

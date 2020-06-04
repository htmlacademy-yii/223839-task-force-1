<?php

namespace frontend\tests\functional;

use frontend\models\forms\TasksFilterForms;
use frontend\models\Users;
use frontend\tests\fixtures\CategoriesFixtures;
use frontend\tests\fixtures\CitiesFixtures;
use frontend\tests\fixtures\TasksFixtures;
use frontend\tests\fixtures\UsersFixtures;
use frontend\tests\FunctionalTester;

class AuthorizedUserInTasksPage
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
        $I->seeElement('#extraFields-' . TasksFilterForms::WITHOUT_RESPONSES, [
          'value' => TasksFilterForms::WITHOUT_RESPONSES
        ]);
        $I->seeElement('#extraFields-' . TasksFilterForms::REMOTE_WORK, [
          'value' => TasksFilterForms::REMOTE_WORK
        ]);
        $I->seeElement('#tasksfilterforms-period');
        $I->seeOptionIsSelected('#tasksfilterforms-period', 'За неделю');
        $I->seeElement('#tasksfilterforms-search');
    }

    public function setWithoutResponsesExtraFieldFilter(FunctionalTester $I)
    {
        $I->submitForm('form.search-task__form', [
          'TasksFilterForms[extraFields]' => [TasksFilterForms::WITHOUT_RESPONSES]
        ]);
        $I->seeInCurrentUrl('/tasks');

        $I->seeCheckboxIsChecked('#extraFields-' . TasksFilterForms::WITHOUT_RESPONSES);
        $I->dontSeeCheckboxIsChecked('#extraFields-' . TasksFilterForms::REMOTE_WORK);
        $I->seeInField('#tasksfilterforms-search', '');
    }

    public function setRemoteWorkExtraFieldFilter(FunctionalTester $I)
    {
        $I->submitForm('form.search-task__form', [
          'TasksFilterForms[extraFields]' => [TasksFilterForms::REMOTE_WORK]
        ]);
        $I->seeInCurrentUrl('/tasks');

        $I->dontSeeCheckboxIsChecked('#extraFields-' . TasksFilterForms::WITHOUT_RESPONSES);
        $I->seeCheckboxIsChecked('#extraFields-' . TasksFilterForms::REMOTE_WORK);
        $I->seeInField('#tasksfilterforms-search', '');
    }

    public function setSearchFilter(FunctionalTester $I)
    {
        $I->submitForm('form.search-task__form', [
          'TasksFilterForms[search]' => 'search'
        ]);
        $I->seeInCurrentUrl('/tasks');

        $I->seeInField('#tasksfilterforms-search', 'search');
    }

    public function setPeriodFilter(FunctionalTester $I)
    {
        $I->submitForm('form.search-task__form', [
          'TasksFilterForms[period]' => TasksFilterForms::ALL_TIME
        ]);
        $I->seeOptionIsSelected('#tasksfilterforms-period', 'За все время');

        $I->submitForm('form.search-task__form', [
          'TasksFilterForms[period]' => TasksFilterForms::CREATED_TODAY
        ]);
        $I->seeOptionIsSelected('#tasksfilterforms-period', 'За день');

        $I->submitForm('form.search-task__form', [
          'TasksFilterForms[period]' => TasksFilterForms::CREATED_MONTH
        ]);
        $I->seeOptionIsSelected('#tasksfilterforms-period', 'За месяц');
    }

    public function setCategoriesFilter(FunctionalTester $I)
    {
        $I->submitForm('form.search-task__form', [
          'TasksFilterForms[categories]' => [1, 2]
        ]);

        $I->seeCheckboxIsChecked('#category-1');
        $I->seeCheckboxIsChecked('#category-2');
        $I->dontSeeCheckboxIsChecked('#category-3');
        $I->dontSeeCheckboxIsChecked('#category-4');
    }
}

<?php

namespace frontend\tests\functional;

use frontend\models\forms\UsersFiltersForm;
use frontend\models\Users;
use frontend\tests\fixtures\CategoriesFixtures;
use frontend\tests\fixtures\CitiesFixtures;
use frontend\tests\fixtures\TasksFixtures;
use frontend\tests\fixtures\UsersFixtures;
use frontend\tests\FunctionalTester;

class AuthUserInUsersPageCest
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
        $I->amOnPage('/users');
        $I->seeInCurrentUrl('/users');
        $I->seeResponseCodeIs(200);
        $I->see('Исполнители');
    }

    public function pagination(FunctionalTester $I)
    {
        $I->seeElement('.new-task__pagination');
        $I->click('.pagination__item a[data-page=1]');
        $I->seeInCurrentUrl('page=2');
    }

    public function hasSortListInUsersPage(FunctionalTester $I)
    {
        $I->see('Сортировать по:', '.user__search-link');
    }

    public function setRatingSorting(FunctionalTester $I)
    {
        $sorting = UsersFiltersForm::getSortsList()[UsersFiltersForm::SORT_RATING];
        ['name' => $name, 'link' => $link] = $sorting;

        $I->seeLink($name, $link);
        $I->click($name);
        $I->see($name, '.user__search-item--current');
    }

    public function paginationSorting(FunctionalTester $I)
    {
        $sorting = UsersFiltersForm::getSortsList()[UsersFiltersForm::SORT_RATING];
        ['name' => $name, 'link' => $link] = $sorting;
        $this->setRatingSorting($I);

        $I->seeElement('.new-task__pagination');
        $I->click('.pagination__item a[data-page=1]');
        $I->seeInCurrentUrl('page=2');
        $I->see($name, '.user__search-item--current');
    }

    public function setCountOrdersSorting(FunctionalTester $I)
    {
        $sorting = UsersFiltersForm::getSortsList()[UsersFiltersForm::SORT_COUNT_ORDERS];
        ['name' => $name, 'link' => $link] = $sorting;

        $I->seeLink($name, $link);
        $I->click($name);
        $I->see($name, '.user__search-item--current');
    }

    public function setPopularSorting(FunctionalTester $I)
    {
        $sorting = UsersFiltersForm::getSortsList()[UsersFiltersForm::SORT_POPULAR];
        ['name' => $name, 'link' => $link] = $sorting;

        $I->seeLink($name, $link);
        $I->click($name);
        $I->see($name, '.user__search-item--current');
    }

    public function hasFilterForm(FunctionalTester $I)
    {
        $I->seeElement('form.search-task__form');
        $I->see('Категории', 'form.search-task__form');
        $I->see('Дополнительно', 'form.search-task__form');
        $I->seeElement('#' . UsersFiltersForm::FREE_NOW, [
          'value' => UsersFiltersForm::FREE_NOW
        ]);
        $I->seeElement('#' . UsersFiltersForm::ONLINE_NOW, [
          'value' => UsersFiltersForm::ONLINE_NOW
        ]);
        $I->seeElement('#' . UsersFiltersForm::HAS_REVIEWS, [
          'value' => UsersFiltersForm::HAS_REVIEWS
        ]);
        $I->seeElement('#' . UsersFiltersForm::FAVORITES, [
          'value' => UsersFiltersForm::FAVORITES
        ]);
    }

    public function setFreeNowExtraFieldFilter(FunctionalTester $I)
    {
        $I->submitForm('form.search-task__form', [
          'extraFields' => [UsersFiltersForm::FREE_NOW]
        ]);
        $I->seeInCurrentUrl('/users');

        $I->seeCheckboxIsChecked('#' . UsersFiltersForm::FREE_NOW);
        $I->dontSeeCheckboxIsChecked('#' . UsersFiltersForm::ONLINE_NOW);
        $I->dontSeeCheckboxIsChecked('#' . UsersFiltersForm::HAS_REVIEWS);
        $I->dontSeeCheckboxIsChecked('#' . UsersFiltersForm::FAVORITES);
        $I->seeInField('#searchByName', '');
    }

    public function setOnlineNowExtraFieldFilter(FunctionalTester $I)
    {
        $I->submitForm('form.search-task__form', [
          'extraFields' => [UsersFiltersForm::ONLINE_NOW]
        ]);
        $I->seeInCurrentUrl('/users');

        $I->dontSeeCheckboxIsChecked('#' . UsersFiltersForm::FREE_NOW);
        $I->seeCheckboxIsChecked('#' . UsersFiltersForm::ONLINE_NOW);
        $I->dontSeeCheckboxIsChecked('#' . UsersFiltersForm::HAS_REVIEWS);
        $I->dontSeeCheckboxIsChecked('#' . UsersFiltersForm::FAVORITES);
        $I->seeInField('#searchByName', '');
    }

    public function setHasReviewsExtraFieldFilter(FunctionalTester $I)
    {
        $I->submitForm('form.search-task__form', [
          'extraFields' => [UsersFiltersForm::HAS_REVIEWS]
        ]);
        $I->seeInCurrentUrl('/users');

        $I->dontSeeCheckboxIsChecked('#' . UsersFiltersForm::FREE_NOW);
        $I->dontSeeCheckboxIsChecked('#' . UsersFiltersForm::ONLINE_NOW);
        $I->seeCheckboxIsChecked('#' . UsersFiltersForm::HAS_REVIEWS);
        $I->dontSeeCheckboxIsChecked('#' . UsersFiltersForm::FAVORITES);
        $I->seeInField('#searchByName', '');
    }

    public function setFavoritesExtraFieldFilter(FunctionalTester $I)
    {
        $I->submitForm('form.search-task__form', [
          'extraFields' => [UsersFiltersForm::FAVORITES]
        ]);
        $I->seeInCurrentUrl('/users');

        $I->dontSeeCheckboxIsChecked('#' . UsersFiltersForm::FREE_NOW);
        $I->dontSeeCheckboxIsChecked('#' . UsersFiltersForm::ONLINE_NOW);
        $I->dontSeeCheckboxIsChecked('#' . UsersFiltersForm::HAS_REVIEWS);
        $I->seeCheckboxIsChecked('#' . UsersFiltersForm::FAVORITES);
        $I->seeInField('#searchByName', '');
    }

    public function setSearchFilter(FunctionalTester $I)
    {
        $I->submitForm('form.search-task__form', [
          'search' => 'search'
        ]);
        $I->seeInCurrentUrl('/users');

        $I->seeInField('#searchByName', 'search');
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

    public function resetFiltersDueOfSearchFilter(FunctionalTester $I)
    {
        $I->submitForm('form.search-task__form', [
          'categories'  => [1, 2],
          'extraFields' => [UsersFiltersForm::FAVORITES, UsersFiltersForm::HAS_REVIEWS],
          'search'      => 'Виктория Соловьева'
        ]);

        $I->dontSeeCheckboxIsChecked('#category-1');
        $I->dontSeeCheckboxIsChecked('#category-2');
        $I->dontSeeCheckboxIsChecked('#' . UsersFiltersForm::ONLINE_NOW);
        $I->dontSeeCheckboxIsChecked('#' . UsersFiltersForm::HAS_REVIEWS);
        $I->seeInField('#searchByName', 'Виктория Соловьева');
        $I->see('Виктория Соловьева', 'p.link-name');
    }
}

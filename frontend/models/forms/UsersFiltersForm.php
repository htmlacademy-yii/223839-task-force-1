<?php

namespace frontend\models\forms;

use frontend\models\Users;
use frontend\models\UsersSpecializations;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class UsersFiltersForm extends Model
{
    const SORT_RATING = 'rating';
    const SORT_COUNT_ORDERS = 'orders';
    const SORT_POPULAR = 'popular';
    const SORT_LAST_ACTIVITY = 'last_activity';

    const FREE_NOW = 'freenow';
    const ONLINE_NOW = 'online';
    const HAS_REVIEWS = 'reviews';
    const FAVORITES = 'favorites';

    public $categories = '';
    public $extraFields = '';
    public string $search = '';
    public string $sort = self::SORT_LAST_ACTIVITY;

    public function attributeLabels()
    {
        return [
          'categories' => 'Категории',
          'extraFields' => 'Дополнительно',
          'sortOn' => 'Сортировать по',
          'search' => 'Поиск по имени'
        ];
    }

    public function rules()
    {
        return [
          [['categories', 'extraFields', 'search'], 'safe']
        ];
    }

    public static function getExtraFieldsList(): array
    {
        return [
          static::FREE_NOW => 'Сейчас свободен',
          static::ONLINE_NOW => 'Сейчас онлайн',
          static::HAS_REVIEWS => 'Есть отзывы',
          static::FAVORITES => 'В избранном'
        ];
    }

    public static function getSortsList(): array
    {
        return [
          static::SORT_RATING => ['name' => 'Рейтингу', 'link' => static::SORT_RATING],
          static::SORT_COUNT_ORDERS => ['name' => 'Числу заказов', 'link' => static::SORT_COUNT_ORDERS],
          static::SORT_POPULAR => ['name' => 'Популярности', 'link' => static::SORT_POPULAR]
          // static::SORT_LAST_ACTIVITY => ['name' => 'Последней активности', 'link' => static::SORT_LAST_ACTIVITY],
        ];
    }

    public function search(array $data): ActiveDataProvider
    {
        $query = Users::find()
          ->select(['users.*'])
          ->andWhere(['role' => Users::ROLE_PERFORMER])
          ->with([
            'reviewsPerformer',
            'tasksPerformer',
            'usersSpecializations',
            'categories'
          ]);

        $this->setSort($query, $data);

        $dataProvider = new ActiveDataProvider([
          'query' => $query,
          'pagination' => ['pageSize' => 5],
          'sort' => [
            'attributes' => [
              'last_activity',
              'rating' => SORT_DESC,
              'tasks_counter',
              'visit_counter'
            ]
          ]
        ]);

        if ($this->isLoadWithoutFilters($data)) {
            return $dataProvider;
        }

        $data = $this->getFilterData($data);

        $this->setFilters($query, $data);

        $data = $this->getReadyDataToLoad($query, $data);

        if ($this->validate()) {
            $this->load($data);
        }

        return $dataProvider;
    }

    private function getReadyDataToLoad(ActiveQuery $query, array $data): array
    {
        if (empty($this->search)) {
            $accumulated = $data;
            $data = [];
            $data[$this->formName()] = $accumulated;
        } else {
            $data = []; // reset filters to form
            $data[$this->formName()] = $this->search;
        }

        return $data;
    }

    private function setSort(ActiveQuery $query, array $data): void
    {
        $sorting = is_null(ArrayHelper::getValue($data, 'sort'))
          ? static::SORT_LAST_ACTIVITY // default sorting
          : ArrayHelper::getValue($data, 'sort');

        $sorts = [
          static::SORT_LAST_ACTIVITY => [$this, 'setSortLastActivity'],
          static::SORT_RATING => [$this, 'setSortRating'],
          static::SORT_COUNT_ORDERS => [$this, 'setSortCountOrders'],
          static::SORT_POPULAR => [$this, 'setSortPopular'],
        ];

        foreach ($sorts as $item) {
            if (ArrayHelper::keyExists($sorting, $sorts)) {
                call_user_func($sorts[$sorting], $query);
            }
        }
    }

    private function setSortLastActivity(ActiveQuery $query): void
    {
        $query->orderBy(['last_activity' => SORT_DESC]);

        $this->sort = static::SORT_LAST_ACTIVITY;
    }

    private function setSortRating(ActiveQuery $query): void
    {
        $query->addSelect(['AVG(reviews.rating) AS rating'])
          ->joinWith('reviewsPerformer', false)
          ->groupBy('users.id')
          ->orderBy(['rating' => SORT_DESC]);

        $this->sort = static::SORT_RATING;
    }

    private function setSortCountOrders(ActiveQuery $query): void
    {
        $query->addSelect(['COUNT(tasks.performer_id) AS tasks_counter'])
          ->joinWith('tasksPerformer', false)
          ->groupBy('users.id')
          ->orderBy(['tasks_counter' => SORT_DESC]);

        $this->sort = static::SORT_COUNT_ORDERS;
    }

    private function setSortPopular(ActiveQuery $query): void
    {
        $query->orderBy(['visit_counter' => SORT_DESC]);

        $this->sort = static::SORT_POPULAR;
    }

    private function setFilters(ActiveQuery $query, array $data): void
    {
        $this->setCategoriesFilter($query, $data);

        $this->setExtraFieldsFilters($query, $data);

        $this->setSearchByUserNameFilter($query, $data);
    }

    /**
     * if the search field is not empty, resets the all filters
     *
     * @param ActiveQuery $query
     * @param array $data
     */
    private function setSearchByUserNameFilter(ActiveQuery $query, array $data): void
    {
        if (!empty($search = (string)ArrayHelper::getValue($data, 'search'))) {
            $this->search = $search;

            $query->where(['id' => Users::findByUserName($search)->column()]);
        }
    }

    private function setCategoriesFilter(ActiveQuery $query, array $data): void
    {
        if (!empty($categories = ArrayHelper::getValue($data, 'categories'))) {
            $query->andFilterWhere(['id' => UsersSpecializations::getPerformersInCategories($categories)->column()]);
        }
    }

    private function setExtraFieldsFilters(ActiveQuery $query, array $data): void
    {
        if (!empty($extraFields = $this->getExtraFields($data))) {
            $extraFieldsFilters = [
              static::FREE_NOW => [$this, 'setFreeNowExtraFieldsFilter'],
              static::ONLINE_NOW => [$this, 'setOnlineNowExtraFieldsFilter'],
              static::HAS_REVIEWS => [$this, 'setHasReviewsExtraFieldsFilter'],
              static::FAVORITES => [$this, 'setFavoritesExtraFieldsFilter']
            ];

            foreach ($extraFields as $extraField) {
                if (ArrayHelper::keyExists($extraField, $extraFieldsFilters)) {
                    call_user_func($extraFieldsFilters[$extraField], $query);
                }
            }
        }
    }

    private function setFreeNowExtraFieldsFilter(ActiveQuery $query): void
    {
        $query->andFilterWhere(['NOT IN', 'id', Users::getPerformersHasTasksNow()]);
    }

    private function setOnlineNowExtraFieldsFilter(ActiveQuery $query): void
    {
        $query->andFilterWhere([
          '>',
          'last_activity',
          new Expression('CURRENT_TIMESTAMP - INTERVAL 1800 SECOND')
        ]);
    }

    private function setHasReviewsExtraFieldsFilter(ActiveQuery $query): void
    {
        $query->andFilterWhere(['id' => Users::getPerformersHasReviews()]);
    }

    private function setFavoritesExtraFieldsFilter(ActiveQuery $query): void
    {
        $userID = 1; // TODO исправить когда появится возможность добавлять в избранное

        $query->andFilterWhere(['id' => Users::getBookmarkedUsersForUser($userID)]);
    }

    private function getExtraFields(array $data): array
    {
        return empty($extraFields = ArrayHelper::getValue($data, 'extraFields')) ? [] : $extraFields;
    }

    private function isLoadWithoutFilters(array $data): bool
    {
        return !ArrayHelper::keyExists($this->formName(), $data) || ArrayHelper::keyExists('sort', $data);
    }

    private function getFilterData($data)
    {
        return ArrayHelper::getValue($data, $this->formName());
    }
}

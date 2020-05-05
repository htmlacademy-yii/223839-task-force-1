<?php

namespace frontend\models;

use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class UsersFiltersForm extends Model
{
    const USER_SORT_RATING = 'rating';
    const USER_SORT_COUNT_ORDERS = 'orders';
    const USER_SORT_POPULAR = 'popular';
    const USER_SORT_LAST_ACTIVITY = 'last_activity';

    const FREE_NOW = 'freenow';
    const ONLINE_NOW = 'online';
    const HAS_RESPONSES = 'responses';
    const FAVORITES = 'favorites';

    public $categories = '';
    public $extraFields = '';
    public string $search = '';

    private array $data = [];
    private ActiveQuery $query;

    public function attributeLabels(): array
    {
        return [
            'categories' => 'Категории',
            'extraFields' => 'Дополнительно',
            'sortOn' => 'Сортировать по',
            'search' => 'Поиск по имени'
        ];
    }

    public function rules(): array
    {
        return [
            [['categories', 'extraFields', 'sortOn', 'search'], 'safe']
        ];
    }

    public static function getExtraFieldsList(): array
    {
        return [
            self::FREE_NOW => 'Сейчас свободен',
            self::ONLINE_NOW => 'Сейчас онлайн',
            self::HAS_RESPONSES => 'Есть отзывы',
            self::FAVORITES => 'В избранном'
        ];
    }

    public function setSort(string $sort, ActiveQuery $query): ActiveQuery
    {
        switch ($sort) {
            case self::USER_SORT_LAST_ACTIVITY:
                return $query->orderBy(['last_activity' => SORT_DESC]);
            case self::USER_SORT_RATING:
                $query = Users::find()
                    ->select(
                        [
                            'users.*',
                            'AVG(reviews.rating) AS rating'
                        ]
                    )
                    ->andFilterWhere(['=', 'role', 'performer'])
                    ->joinWith('reviewsPerformer', false)
                    ->groupBy('users.id')
                    ->orderBy(['rating' => SORT_DESC]);
                return $query;
            case self::USER_SORT_COUNT_ORDERS:
                $query = Users::find()
                    ->select(
                        [
                            'users.*',
                            'count(tasks.performer_id) as tasksCounter'
                        ]
                    )
                    ->andFilterWhere(['=', 'role', 'performer'])
                    ->joinWith('tasksPerformer', false)
                    ->groupBy('users.id')
                    ->orderBy(['tasksCounter' => SORT_DESC]);
                return $query;
            case self::USER_SORT_POPULAR:
                return $query->orderBy(['visit_counter' => SORT_DESC]);
        }
    }

    public function setData(array $data): void
    {
        $this->data = ArrayHelper::getValue($data, $this->formName());
    }

    public function setFilters(ActiveQuery $query): void
    {
        $this->query = $query;

        $this->setCategoriesFilter();
        $this->setExtraFieldsFilters();
        $this->setSearchFilter();
    }

    private function setCategoriesFilter(): void
    {
        $categories = ArrayHelper::getValue($this->data, 'categories');
        $categories = UserSpecializations::find()
            ->select(['performer_id'])
            ->andFilterWhere(['IN', 'category_id', $categories])
            ->all();

        $performersId = [];
        foreach ($categories as $category) {
            $performersId[] = $category->performer_id;
        }

        $this->query->andFilterWhere(['IN', 'id', $performersId]);
    }

    /**
     * Метод сбрасывает все выбранные фильтры
     * и ищет пользователя с нестрогим совпадением по его имени и фамилии.
     */
    private function setSearchFilter(): void
    {
        if (!empty($this->getSearch())) {
            $this->query->where(['LIKE', "CONCAT(first_name, '', last_name)", "{$this->getSearch()}"]);
        }
    }

    private function setExtraFieldsFilters(): void
    {
        if (!empty($this->getExtraFields())) {
            foreach (self::getExtraFieldsList() as $key => $value) {
                if (ArrayHelper::keyExists($key, $this->getExtraFields()) && $key === self::FREE_NOW) {
                    $tasks = Tasks::find()
                        ->select('performer_id')
                        ->distinct()
                        ->where(['=', 'status', Tasks::STATUS_ACTIVE])
                        ->all();

                    $performersWithoutTasks = [];
                    foreach ($tasks as $task) {
                        $performersWithoutTasks[] = ArrayHelper::getValue($task, 'performer_id');
                    }

                    $this->query->andFilterWhere(['NOT IN', 'id', $performersWithoutTasks]);
                }
                if (ArrayHelper::keyExists($key, $this->getExtraFields()) && $key === self::ONLINE_NOW) {
                    $this->query->andFilterWhere(
                        [
                            '>',
                            'last_activity',
                            new Expression('CURRENT_TIMESTAMP - INTERVAL 1800 SECOND')
                        ]
                    );
                }
                if (ArrayHelper::keyExists($key, $this->getExtraFields()) && $key === self::HAS_RESPONSES) {
                    $reviews = Reviews::find()
                        ->select(['performer_id'])
                        ->distinct()
                        ->all();;

                    $perfromerWithReviews = [];
                    foreach ($reviews as $review => $value) {
                        $perfromerWithReviews[] = $value->performer_id;
                    }

                    $this->query->andFilterWhere(['IN', 'id', $perfromerWithReviews]);
                }
                if (ArrayHelper::keyExists($key, $this->getExtraFields()) && $key === self::FAVORITES) {
                    $usersId = [1];
                    // TODO исправить когда появится возможность добавлять в избранное
                    $bookmarkedPerformers = BookmarkedUsers::find()
                        ->select(['bookmarked_user_id'])
                        ->where(['IN', 'user_id', $usersId])
                        ->all();

                    $bookmarkedPerformersId = [];
                    foreach ($bookmarkedPerformers as $bookmarkedPerformer) {
                        $bookmarkedPerformersId[] = $bookmarkedPerformer->bookmarked_user_id;
                    }

                    $this->query->andFilterWhere(['IN', 'id', $bookmarkedPerformersId]);
                }
            }
        }
    }

    private function getSearch(): string
    {
        return (string)ArrayHelper::getValue($this->data, 'search');
    }

    private function getExtraFields(): array
    {
        $extraFields = ArrayHelper::getValue($this->data, 'extraFields');

        if (empty($extraFields)) {
            return [];
        }

        return array_flip($extraFields);
    }
}

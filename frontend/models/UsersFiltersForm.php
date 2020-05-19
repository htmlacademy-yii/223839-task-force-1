<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
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
            [['categories', 'extraFields', 'search'], 'safe']
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

    public function search(array $data): ActiveDataProvider
    {
        $this->query = Users::find()
            ->select(['users.*'])
            ->andWhere(['role' => Users::ROLE_PERFORMER])
            ->with([
                'reviewsPerformer',
                'tasksPerformer',
                'usersSpecializations',
                'categories'
            ]);

        $sort = \Yii::$app->request->get('sort');
        if (isset($sort)) {
            $this->setSort($sort);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $this->query,
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

        if (!$this->load($data) && $this->validate()) {
            return $dataProvider;
        }

        $this->data = ArrayHelper::getValue($data, $this->formName());
        $this->setFilters();

        return $dataProvider;
    }

    public function setSort(string $sort): void
    {
        switch ($sort) {
            case self::USER_SORT_LAST_ACTIVITY:
                $this->query->orderBy(['last_activity' => SORT_DESC]);
                break;
            case self::USER_SORT_RATING:
                $this->query
                    ->addSelect(['AVG(reviews.rating) AS rating'])
                    ->joinWith('reviewsPerformer', false)
                    ->groupBy('users.id')
                    ->orderBy(['rating' => SORT_DESC]);
                break;
            case self::USER_SORT_COUNT_ORDERS:
                $this->query
                    ->addSelect(['COUNT(tasks.performer_id) AS tasks_counter'])
                    ->joinWith('tasksPerformer', false)
                    ->groupBy('users.id')
                    ->orderBy(['tasks_counter' => SORT_DESC]);
                break;
            case self::USER_SORT_POPULAR:
                $this->query->orderBy(['visit_counter' => SORT_DESC]);
                break;
        }
    }

    public function setFilters(): void
    {
        $this->setCategoriesFilter();

        $this->setExtraFieldsFilters();

        $this->setSearchFilter();
    }

    private function setCategoriesFilter(): void
    {
        $categories = UsersSpecializations::find()
            ->select(['performer_id'])
            ->andFilterWhere(['category_id' => ArrayHelper::getValue($this->data, 'categories')])
            ->all();

        $performersIDs = ArrayHelper::getColumn($categories, 'performer_id');

        $this->query->andFilterWhere(['id' => $performersIDs]);
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
            foreach ($this->getExtraFields() as $value) {
                switch ($value) {
                    case self::FREE_NOW:
                        $this->setFreeNowExtraFieldsFilter();
                        break;
                    case self::ONLINE_NOW:
                        $this->setOnlineNowExtraFieldsFilter();
                        break;
                    case self::HAS_RESPONSES:
                        $this->setHasResponsesExtraFieldsFilter();
                        break;
                    case self::FAVORITES:
                        $this->setFavoritesExtraFieldsFilter();
                        break;
                }
            }
        }
    }

    private function setFreeNowExtraFieldsFilter(): void
    {
        $tasks = Tasks::find()
            ->select('performer_id')
            ->distinct()
            ->where(['status' => Tasks::STATUS_ACTIVE])
            ->all();

        $performersWithoutTasks = ArrayHelper::getColumn($tasks, 'performer_id');

        $this->query->andFilterWhere(['NOT IN', 'id', $performersWithoutTasks]);
    }

    private function setOnlineNowExtraFieldsFilter(): void
    {
        $this->query->andFilterWhere([
            '>',
            'last_activity',
            new Expression('CURRENT_TIMESTAMP - INTERVAL 1800 SECOND')
        ]);
    }

    private function setHasResponsesExtraFieldsFilter(): void
    {
        $reviews = Reviews::find()
            ->select(['performer_id'])
            ->distinct()
            ->all();

        $perfomerWithReviews = ArrayHelper::getColumn($reviews, 'performer_id');

        $this->query->andFilterWhere(['id' => $perfomerWithReviews]);
    }

    private function setFavoritesExtraFieldsFilter(): void
    {
        $usersID = [1]; // TODO исправить когда появится возможность добавлять в избранное

        $bookmarkedPerformers = Bookmarked_users::find()
            ->select(['bookmarked_user_id'])
            ->where(['user_id' => $usersID])
            ->all();

        $bookmarkedPerformersIDs = ArrayHelper::getColumn($bookmarkedPerformers, 'bookmarked_user_id');

        $this->query->andFilterWhere(['id' => $bookmarkedPerformersIDs]);
    }

    private function getSearch(): string
    {
        return (string)ArrayHelper::getValue($this->data, 'search');
    }

    private function getExtraFields(): array
    {
        $extraFields = ArrayHelper::getValue($this->data, 'extraFields');

        return empty($extraFields) ? [] : $extraFields;
    }
}

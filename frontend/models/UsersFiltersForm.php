<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

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

    public function search(array $data): ActiveDataProvider
    {
        $this->query = Users::find()
            ->andWhere(['role' => Users::ROLE_PERFORMER])
            ->with([
                'reviewsPerformer',
                'tasksPerformer',
                'userSpecializations',
                'categories'
            ]);

        $sort = \Yii::$app->request->get('sort');
        if (isset($sort)) {
            $this->setSort($sort);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $this->query,
            'pagination' => [
                'pageSize' => 5,
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
                $this->query = Users::find()
                    ->select([
                        'users.*',
                        'AVG(reviews.rating) AS rating'
                    ])
                    ->andFilterWhere(['role' => 'performer'])
                    ->joinWith('reviewsPerformer', false)
                    ->groupBy('users.id')
                    ->orderBy(['rating' => SORT_DESC]);
                break;
            case self::USER_SORT_COUNT_ORDERS:
                $this->query = Users::find()
                    ->select([
                        'users.*',
                        'count(tasks.performer_id) AS tasksCounter'
                    ])
                    ->andFilterWhere(['role' => 'performer'])
                    ->joinWith('tasksPerformer', false)
                    ->groupBy('users.id')
                    ->orderBy(['tasksCounter' => SORT_DESC]);
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
        $categories = ArrayHelper::getValue($this->data, 'categories');
        $categories = UserSpecializations::find()
            ->select(['performer_id'])
            ->andFilterWhere(['IN', 'category_id', $categories])
            ->all();

        $performersIDs = [];
        foreach ($categories as $category) {
            $performersIDs[] = $category->performer_id;
        }

        $this->query->andFilterWhere(['IN', 'id', $performersIDs]);
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
            ->where(['=', 'status', Tasks::STATUS_ACTIVE])
            ->all();

        $performersWithoutTasks = [];
        foreach ($tasks as $task) {
            $performersWithoutTasks[] = ArrayHelper::getValue($task, 'performer_id');
        }

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
            ->all();;

        $perfromerWithReviews = [];
        foreach ($reviews as $value) {
            $perfromerWithReviews[] = $value->performer_id;
        }

        $this->query->andFilterWhere(['IN', 'id', $perfromerWithReviews]);
    }

    private function setFavoritesExtraFieldsFilter(): void
    {
        $usersId = [1]; // TODO исправить когда появится возможность добавлять в избранное
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

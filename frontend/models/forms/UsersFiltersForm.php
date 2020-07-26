<?php

namespace frontend\models\forms;

use frontend\models\Users;
use frontend\services\filters\users\filters\CategoriesFilter;
use frontend\services\filters\users\filters\ExtraFieldsFilter;
use frontend\services\filters\users\filters\SearchByUserNameFilter;
use frontend\services\filters\users\sorts\CountOrdersSort;
use frontend\services\filters\users\sorts\LastActivitySort;
use frontend\services\filters\users\sorts\PopularSort;
use frontend\services\filters\users\sorts\RatingSort;
use yii\data\ActiveDataProvider;

class UsersFiltersForm extends FilterForm
{
    const
        SORT_RATING = 'rating',
        SORT_COUNT_ORDERS = 'orders',
        SORT_POPULAR = 'popular',
        SORT_LAST_ACTIVITY = 'last_activity';

    const
        FREE_NOW = 'freenow',
        ONLINE_NOW = 'online',
        HAS_REVIEWS = 'reviews',
        FAVORITES = 'favorites';

    public        $categories  = '';
    public        $extraFields = '';
    public string $search      = '';
    public string $sort        = self::SORT_LAST_ACTIVITY;

    public static function getExtraFieldsList(): array
    {
        return [
            static::FREE_NOW    => 'Сейчас свободен',
            static::ONLINE_NOW  => 'Сейчас онлайн',
            static::HAS_REVIEWS => 'Есть отзывы',
            static::FAVORITES   => 'В избранном'
        ];
    }

    public static function getSortsList(): array
    {
        return [
            static::SORT_RATING       => ['name' => 'Рейтингу', 'link' => static::SORT_RATING],
            static::SORT_COUNT_ORDERS => ['name' => 'Числу заказов', 'link' => static::SORT_COUNT_ORDERS],
            static::SORT_POPULAR      => ['name' => 'Популярности', 'link' => static::SORT_POPULAR]
            // static::SORT_LAST_ACTIVITY => ['name' => 'Последней активности', 'link' => static::SORT_LAST_ACTIVITY],
        ];
    }

    public function attributeLabels()
    {
        return [
            'categories'  => 'Категории',
            'extraFields' => 'Дополнительно',
            'sortOn'      => 'Сортировать по',
            'search'      => 'Поиск по имени'
        ];
    }

    public function rules()
    {
        return [
            [['categories', 'extraFields', 'search'], 'safe']
        ];
    }

    public function search(array $data, int $pageSize = 5): ActiveDataProvider
    {
        $query = Users::find()
            ->select(['users.*'])
            ->andWhere(['role' => Users::ROLE_PERFORMER])
            ->with(['reviewsPerformer', 'tasksPerformer', 'usersSpecializations', 'categories']);

        $data = $this->formatData($data);

        if ($this->validate()) {
            $this->load([$this->formName() => $data]);
        }

        if (isset($data['sort'])) {
            $query = $this->setFilters(
                $query, $data,
                [
                    static::SORT_LAST_ACTIVITY => new LastActivitySort(),
                    static::SORT_COUNT_ORDERS  => new CountOrdersSort(),
                    static::SORT_POPULAR       => new PopularSort(),
                    static::SORT_RATING        => new RatingSort()
                ][$data['sort']]
            );
            $this->sort = $data['sort'];
        }

        $query = $this->setFilters(
            $query, $data,
            new CategoriesFilter(),
            new ExtraFieldsFilter(),
            new SearchByUserNameFilter(),
        );

        return new ActiveDataProvider([
            'query'      => $query,
            'pagination' => ['pageSize' => $pageSize],
            'sort'       => [
                'attributes' => [
                    'last_activity',
                    'rating' => SORT_DESC,
                    'tasks_counter',
                    'visit_counter'
                ]
            ]
        ]);
    }

    private function formatData(array $data)
    {
        $search = isset($data['search']) ? $data['search'] : '';

        if (!empty($search)) {
            $data = ['search' => $search];
        }

        return $data;
    }
}
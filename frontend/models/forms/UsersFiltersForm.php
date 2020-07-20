<?php

namespace frontend\models\forms;

use frontend\DTO\FilterFormDTO;
use frontend\services\filters\users\CategoriesFilter;
use frontend\services\filters\users\ExtraFieldsFilter;
use frontend\services\filters\users\SearchByUserNameFilter;
use frontend\services\filters\users\sorts\CountOrdersSort;
use frontend\services\filters\users\sorts\LastActivitySort;
use frontend\services\filters\users\sorts\PopularSort;
use frontend\services\filters\users\sorts\RatingSort;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

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

    public function search(FilterFormDTO $DTO): ActiveDataProvider
    {
        $this->setSorts(
            $DTO->getQuery(),
            $DTO->getData()
        );

        $this->setFilters(
            $DTO->getQuery(),
            $this->getFormData($DTO->getData())
        );

        if (!empty($this->getFormData($DTO->getData())['search'])) {
            $this->clearFilters($DTO);
        }

        if ($this->validate()) {
            $this->load($DTO->getData());
        }

        return new ActiveDataProvider([
            'query'      => $DTO->getQuery(),
            'pagination' => ['pageSize' => $DTO->getPageSize()],
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

    private function setSorts(ActiveQuery $query, array $data): void
    {
        $sorting = $this->getSorting($data);

        $sorts = [
            static::SORT_LAST_ACTIVITY => new LastActivitySort($query),
            static::SORT_RATING        => new RatingSort($query),
            static::SORT_COUNT_ORDERS  => new CountOrdersSort($query),
            static::SORT_POPULAR       => new PopularSort($query),
        ];

        if (ArrayHelper::keyExists($sorting, $sorts)) {
            $sorts[$sorting]->execute();
            $this->sort = $sorting;
        }
    }

    private function setFilters(ActiveQuery $query, array $data): void
    {
        $this->setFilter(new CategoriesFilter($query, $data));
        $this->setFilter(new ExtraFieldsFilter($query, $data));
        $this->setFilter(new SearchByUserNameFilter($query, $data));
    }


    private function getSorting(array $data): string
    {
        $sort = ArrayHelper::getValue($data, 'sort');

        return is_null($sort)
            ? static::SORT_LAST_ACTIVITY // default sorting
            : $sort;
    }

    private function clearFilters(FilterFormDTO $DTO): void
    {
        $data[$this->formName()]['search'] = $this->getFormData($DTO->getData())['search'];;

        $DTO->setData($data);
    }
}

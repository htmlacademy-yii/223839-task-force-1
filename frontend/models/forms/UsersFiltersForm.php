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
    const USER_SORT_RATING = 'rating';
    const USER_SORT_COUNT_ORDERS = 'orders';
    const USER_SORT_POPULAR = 'popular';
    const USER_SORT_LAST_ACTIVITY = 'last_activity';

    const FREE_NOW = 'freenow';
    const ONLINE_NOW = 'online';
    const HAS_REVIEWS = 'reviews';
    const FAVORITES = 'favorites';

    public $categories = '';
    public $extraFields = '';
    public string $search = '';

    private array $data = [];
    private ActiveQuery $query;

    private array $inQuery = [];
    private array $outQuery = [];

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
          self::HAS_REVIEWS => 'Есть отзывы',
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

        if (isset($data['sort'])) {
            $this->setSort($data['sort']);
        } else {
            ArrayHelper::setValue($data, 'sort', self::USER_SORT_LAST_ACTIVITY);
            $this->setSort($data['sort']);
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
        $user = new Users();

        $this->setCategoriesFilter();

        $this->setExtraFieldsFilters($user);

        $this->inQuery = array_diff($this->inQuery, $this->outQuery);

        $this->query->andFilterWhere(['id' => $this->inQuery]);

        $this->setSearchFilter();
    }

    private function setCategoriesFilter(): void
    {
        if (!empty(ArrayHelper::getValue($this->data, 'categories'))) {
            $performers = UsersSpecializations::find()
              ->distinct()
              ->select(['performer_id'])
              ->andFilterWhere(['category_id' => ArrayHelper::getValue($this->data, 'categories')])
              ->column();

            $this->inQuery = $performers;
        }
    }

    /**
     * Метод сбрасывает все выбранные фильтры
     * и ищет пользователя с нестрогим совпадением по его имени и фамилии.
     */
    private function setSearchFilter(): void
    {
        $search = (string)ArrayHelper::getValue($this->data, 'search');

        if (!empty($search)) {
            $this->query->where([
              'LIKE',
              "CONCAT(first_name, '', last_name)",
              $search
            ]);
        }
    }

    private function setExtraFieldsFilters(Users $user): void
    {
        $extraFields = $this->getExtraFields();

        if (!empty($extraFields)) {
            $extraFieldsFilters = [
              self::FREE_NOW => [$this, 'setFreeNowExtraFieldsFilter'],
              self::ONLINE_NOW => [$this, 'setOnlineNowExtraFieldsFilter'],
              self::HAS_REVIEWS => [$this, 'setHasReviewsExtraFieldsFilter'],
              self::FAVORITES => [$this, 'setFavoritesExtraFieldsFilter']
            ];

            foreach ($extraFields as $extraField) {
                if (ArrayHelper::keyExists($extraField, $extraFieldsFilters)) {
                    call_user_func($extraFieldsFilters[$extraField], $user);
                }
            }
        }
    }

    private function setFreeNowExtraFieldsFilter(Users $user): void
    {
        $this->outQuery = ArrayHelper::merge($this->outQuery, $user->getPerformersHasTasksNow());
    }

    private function setOnlineNowExtraFieldsFilter(): void
    {
        $this->query->andFilterWhere([
          '>',
          'last_activity',
          new Expression('CURRENT_TIMESTAMP - INTERVAL 1800 SECOND')
        ]);
    }

    private function setHasReviewsExtraFieldsFilter(Users $user): void
    {
        $this->inQuery = empty($this->inQuery)
          ? $user->getPerformersHasReviews()
          : array_intersect($this->inQuery, $user->getPerformersHasReviews());
    }


    private function setFavoritesExtraFieldsFilter(Users $user): void
    {
        $userID = 1; // TODO исправить когда появится возможность добавлять в избранное

        $this->inQuery = empty($this->inQuery)
          ? $user->getBookmarkedUsersForUser($userID)
          : array_intersect($this->inQuery, $user->getBookmarkedUsersForUser($userID));
    }

    private function getExtraFields(): array
    {
        $extraFields = ArrayHelper::getValue($this->data, 'extraFields');

        return empty($extraFields) ? [] : $extraFields;
    }
}

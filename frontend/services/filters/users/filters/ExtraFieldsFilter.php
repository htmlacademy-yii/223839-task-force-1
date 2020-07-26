<?php


namespace frontend\services\filters\users\filters;

use frontend\models\forms\UsersFiltersForm as Form;
use frontend\models\Users;
use frontend\services\filters\Filter;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class ExtraFieldsFilter implements Filter
{
    public function setFilter(ActiveQuery $query, array $data): ActiveQuery
    {
        $extraFields = $this->getExtraFields($data);

        if (isset($data['extraFields']) && !empty($extraFields)) {
            $extraFieldsFilters = [
                Form::FREE_NOW    => [$this, 'setFreeNow'],
                Form::ONLINE_NOW  => [$this, 'setOnlineNow'],
                Form::HAS_REVIEWS => [$this, 'setHasReviews'],
                Form::FAVORITES   => [$this, 'setFavorites']
            ];

            foreach ($extraFields as $extraField) {
                if (ArrayHelper::keyExists($extraField, $extraFieldsFilters)) {
                    call_user_func($extraFieldsFilters[$extraField], $query);
                }
            }
        }

        return $query;
    }

    private function setFreeNow(ActiveQuery $query): void
    {
        $query->andFilterWhere(['NOT IN', 'id', Users::getPerformersHasTasksNow()]);
    }

    private function setOnlineNow(ActiveQuery $query): void
    {
        $query->andFilterWhere([
            '>',
            'last_activity',
            new Expression('CURRENT_TIMESTAMP - INTERVAL 1800 SECOND')
        ]);
    }

    private function setHasReviews(ActiveQuery $query): void
    {
        $query->andFilterWhere(['id' => Users::getPerformersHasReviews()]);
    }

    private function setFavorites(ActiveQuery $query): void
    {
        $userID = 1;

        $query->andFilterWhere(['id' => Users::getBookmarkedUsersForUser($userID)]);
    }

    private function getExtraFields(array $data): array
    {
        $extraFields = ArrayHelper::getValue($data, 'extraFields');

        return empty($extraFields)
            ? []
            : $extraFields;
    }
}
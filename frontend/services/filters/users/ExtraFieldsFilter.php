<?php


namespace frontend\services\filters\users;

use frontend\models\forms\UsersFiltersForm as Form;
use frontend\models\Users;
use frontend\services\filters\Filter;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class ExtraFieldsFilter extends Filter
{
    public function execute(): void
    {
        $extraFields = $this->getExtraFields();

        if (isset($this->data['extraFields']) && !empty($extraFields)) {
            $extraFieldsFilters = [
                Form::FREE_NOW    => [$this, 'setFreeNow'],
                Form::ONLINE_NOW  => [$this, 'setOnlineNow'],
                Form::HAS_REVIEWS => [$this, 'setHasReviews'],
                Form::FAVORITES   => [$this, 'setFavorites']
            ];

            foreach ($extraFields as $extraField) {
                if (ArrayHelper::keyExists($extraField, $extraFieldsFilters)) {
                    call_user_func($extraFieldsFilters[$extraField], $this->query);
                }
            }
        }
    }

    private function setFreeNow(): void
    {
        $this->query->andFilterWhere(['NOT IN', 'id', Users::getPerformersHasTasksNow()]);
    }

    private function setOnlineNow(): void
    {
        $this->query->andFilterWhere([
            '>',
            'last_activity',
            new Expression('CURRENT_TIMESTAMP - INTERVAL 1800 SECOND')
        ]);
    }

    private function setHasReviews(): void
    {
        $this->query->andFilterWhere(['id' => Users::getPerformersHasReviews()]);
    }

    private function setFavorites(): void
    {
        $userID = 1;

        $this->query->andFilterWhere(['id' => Users::getBookmarkedUsersForUser($userID)]);
    }

    private function getExtraFields(): array
    {
        $extraFields = ArrayHelper::getValue($this->data, 'extraFields');

        return empty($extraFields)
            ? []
            : $extraFields;
    }
}
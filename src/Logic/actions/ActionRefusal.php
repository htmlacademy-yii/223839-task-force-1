<?php

namespace src\Logic\actions;


use src\error\ActionException;

class ActionRefusal extends Action
{
    public function getPublicName(): string
    {
        return 'Отказаться';
    }

    public function checkRights(int $customerID, int $performerID, int $currentUserID): bool
    {
        if ($performerID !== $currentUserID) {
            throw new ActionException(' доступ запрещен', __FILE__, __LINE__, ActionRefusal::getInnerName());

        } else {
            return $performerID === $currentUserID;
        }
    }
}

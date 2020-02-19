<?php

namespace src\Logic\actions;


use src\error\ActionException;

class ActionCancel extends Action
{
    public function getPublicName(): string
    {
        return 'Отменить';
    }

    public function checkRights(int $customerID, int $performerID, int $currentUserID): bool
    {
        if ($customerID !== $currentUserID) {
            throw new ActionException(' доступ запрещен', __FILE__, __LINE__, ActionCancel::getInnerName());
        }
        return $customerID === $currentUserID;
    }
}

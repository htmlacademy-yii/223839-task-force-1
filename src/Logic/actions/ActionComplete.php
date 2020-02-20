<?php

namespace src\Logic\actions;


use src\error\ActionException;

class ActionComplete extends Action
{
    public function getPublicName(): string
    {
        return 'Выполнить';
    }

    public function checkRights(int $customerID, int $performerID, int $currentUserID): bool
    {
        if ($customerID !== $currentUserID) {
            throw new AccessIsDeniedException('Access is denied');
        }

        return true;
    }
}

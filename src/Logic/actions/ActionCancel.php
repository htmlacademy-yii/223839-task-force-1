<?php

namespace src\Logic\actions;


use src\exceptions\AccessIsDeniedException;

class ActionCancel extends Action
{
    public function getPublicName(): string
    {
        return 'Отменить';
    }

    public function checkRights(int $customerID, int $performerID, int $currentUserID): bool
    {
        if ($customerID !== $currentUserID) {
            throw new AccessIsDeniedException();
        }

        return true;
    }
}

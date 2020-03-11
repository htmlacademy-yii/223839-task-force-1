<?php

namespace src\Logic\actions;


use src\exceptions\AccessIsDeniedException;

class ActionComplete extends Action
{
    public function getPublicName(): string
    {
        return 'Выполнить';
    }

    public function checkRights(int $customerID, int $performerID, int $currentUserID): bool
    {
        if ($customerID !== $currentUserID) {
            throw new AccessIsDeniedException();
        }

        return true;
    }
}

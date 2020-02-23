<?php

namespace src\Logic\actions;


use src\error\AccessIsDeniedException;

class ActionStart extends Action
{
    public function getPublicName(): string
    {
        return 'Принять';
    }

    public function checkRights(int $customerID, int $performerID, int $currentUserID): bool
    {
        if ($performerID !== $currentUserID) {
            throw new AccessIsDeniedException();
        }

        return true;
    }
}

<?php

namespace src\Logic\actions;


use src\exceptions\AccessIsDeniedException;

class TaskActionStart extends TaskAction
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

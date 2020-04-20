<?php

namespace Logic\Actions;

use Exceptions\AccessIsDeniedException;

class TaskActionRefusal extends TaskAction
{
    public function getPublicName(): string
    {
        return 'Отказаться';
    }

    public function checkRights(int $customerID, int $performerID, int $currentUserID): bool
    {
        if ($performerID !== $currentUserID) {
            throw new AccessIsDeniedException();
        }

        return true;
    }
}

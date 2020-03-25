<?php

namespace Logic\Actions;

use Exceptions\AccessIsDeniedException;

class TaskActionCancel extends TaskAction
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

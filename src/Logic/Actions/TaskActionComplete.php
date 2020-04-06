<?php

namespace Logic\Actions;

use Exceptions\AccessIsDeniedException;

class TaskActionComplete extends TaskAction
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

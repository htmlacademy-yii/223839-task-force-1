<?php

namespace src\Logic\actions;


class ActionComplete extends Action
{
    public function getPublicName()
    {
        return 'Выполнить';
    }

    public function checkRights($customerID, $performerID, $currentUserID)
    {
        return $customerID === $currentUserID;
    }
}

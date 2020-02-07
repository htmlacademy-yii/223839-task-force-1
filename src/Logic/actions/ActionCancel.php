<?php

namespace src\Logic\actions;


class ActionCancel extends Action
{
    public function getPublicName()
    {
        return 'Отменить';
    }

    public function checkRights($customerID, $performerID, $currentUserID)
    {
        return $customerID === $currentUserID;
    }
}

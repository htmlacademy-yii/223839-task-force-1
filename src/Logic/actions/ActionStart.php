<?php

namespace src\Logic\actions;


class ActionStart extends Action
{
    public function getPublicName()
    {
        return 'Принять';
    }

    public function checkRights($customerID, $performerID, $currentUserID)
    {
        return $performerID === $currentUserID;
    }
}

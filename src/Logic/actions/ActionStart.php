<?php

namespace src\Logic\actions;


class ActionStart extends Action
{
    public function getPublicName() : string
    {
        return 'Принять';
    }

    public function checkRights(int $customerID, int $performerID, int $currentUserID) : bool
    {
        return $performerID === $currentUserID;
    }
}

<?php

namespace src\Logic\actions;


class ActionCancel extends Action
{
    public function getPublicName() : string
    {
        return 'Отменить';
    }

    public function checkRights(int $customerID, int $performerID, int $currentUserID) : bool
    {
        return $customerID === $currentUserID;
    }
}

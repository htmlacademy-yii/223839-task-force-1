<?php

namespace src\Logic\actions;


class ActionRefusal extends Action
{
    public function getPublicName() : string
    {
        return 'Отказаться';
    }

    public function checkRights(int $customerID, int $performerID, int $currentUserID) : bool
    {
        return $performerID === $currentUserID;
    }
}

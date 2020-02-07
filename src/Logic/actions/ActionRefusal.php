<?php

namespace src\Logic\actions;


class ActionRefusal extends Action
{
    public function getPublicName()
    {
        return 'Отказаться';
    }

    public function checkRights($customerID, $performerID, $currentUserID)
    {
        return $performerID === $currentUserID;
    }
}

<?php

namespace src\Logic\actions;


class ActionStart extends Action
{
    public function getPublicName()
    {
        return 'Принять';
    }

    public static function getInnerName()
    {
        return parent::getInnerName();
    }


    public function checkRights($customerID, $performerID, $currentUserID, $taskStatus)
    {
        return $performerID === $currentUserID && $customerID !== $currentUserID && $taskStatus === 0;
    }
}

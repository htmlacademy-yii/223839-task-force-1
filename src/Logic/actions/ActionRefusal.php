<?php

namespace src\Logic\actions;


class ActionRefusal extends Action
{
    public function getPublicName()
    {
        return 'Отказаться';
    }

    public static function getInnerName()
    {
        return parent::getInnerName();
    }


    public function checkRights($customerID, $performerID, $currentUserID, $taskStatus)
    {
        return $performerID === $currentUserID && $customerID !== $currentUserID && $taskStatus === 2;
    }
}

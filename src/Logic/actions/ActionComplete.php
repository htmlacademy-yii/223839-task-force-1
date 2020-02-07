<?php

namespace src\Logic\actions;


class ActionComplete extends Action
{
    public function getPublicName()
    {
        return 'Выполнить';
    }

    public static function getInnerName()
    {
        return parent::getInnerName();
    }


    public function checkRights($customerID, $performerID, $currentUserID, $taskStatus)
    {
        return $performerID !== $currentUserID && $customerID === $currentUserID && $taskStatus === 2;
    }
}

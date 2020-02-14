<?php

namespace src\Logic\actions;


use src\error\ErrorHandler;

class ActionComplete extends Action
{
    public function getPublicName() : string
    {
        return 'Выполнить';
    }

    public function checkRights(int $customerID, int $performerID, int $currentUserID) : bool
    {
        if ($customerID !== $currentUserID) {
            throw new ErrorHandler('Доступ запрещен');
        } else {
            return $customerID === $currentUserID;
        }
    }
}

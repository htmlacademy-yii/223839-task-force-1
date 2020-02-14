<?php

namespace src\Logic\actions;


use src\error\ErrorHandler;

class ActionRefusal extends Action
{
    public function getPublicName() : string
    {
        return 'Отказаться';
    }

    public function checkRights(int $customerID, int $performerID, int $currentUserID) : bool
    {
        if ($performerID !== $currentUserID) {
            throw new ErrorHandler('Доступ запрещен');
        } else {
            return $performerID === $currentUserID;
        }
    }
}

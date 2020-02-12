<?php

namespace src\Logic\actions;


class ActionComplete extends Action
{
    public function getPublicName() :string
    {
        return 'Выполнить';
    }

    public function checkRights(int $customerID, int $performerID, int $currentUserID) : bool
    {
        return $customerID === $currentUserID;
    }
}

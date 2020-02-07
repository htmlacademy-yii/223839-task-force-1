<?php

namespace src\Logic\actions;


abstract class Action
{
    abstract function getPublicName();

    public static function getInnerName()
    {
        $class = explode('\\', static::class);
        return $class[count($class) - 1];
    }

    abstract protected function checkRights($customerID, $performerID, $currentUserID, $taskStatus);
}

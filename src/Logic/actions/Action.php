<?php

namespace src\Logic\actions;


abstract class Action
{
    /**
     * метод для возврата названия
     * @return string
     */
    abstract function getPublicName() : string;

    /**
     * метод для возврата имени класса без namespace
     * @return string
     */
    public static function getInnerName() : string
    {
        $class = explode('\\', static::class);
        return $class[count($class) - 1];
    }

    /**
     * Метод возвращает true или false в зависимости от доступности выполнения этого действия
     * @param int $customerID
     * @param int $performerID
     * @param int $currentUserID
     *
     * @return bool
     */
    abstract protected function checkRights(int $customerID, int $performerID, int $currentUserID) : bool;
}

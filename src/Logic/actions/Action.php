<?php

namespace src\Logic\actions;


abstract class Action
{
    /**
     * метод для возврата имени класса без namespace
     * @return string
     */
    public static function getInnerName() : string
    {
        return static::class;
    }

    /**
     * метод для возврата названия
     * @return string
     */
    abstract function getPublicName() : string;

    /**
     * Метод возвращает true или false в зависимости от доступности выполнения этого действия
     *
     * @param int $customerID
     * @param int $performerID
     * @param int $currentUserID
     *
     * @return bool
     */
    abstract protected function checkRights(int $customerID, int $performerID, int $currentUserID) : bool;
}

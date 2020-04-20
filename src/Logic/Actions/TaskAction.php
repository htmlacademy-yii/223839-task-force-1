<?php

namespace Logic\Actions;


abstract class TaskAction
{
    /**
     * Метод для возврата имени класса
     * @return string
     */
    public static function getInnerName() : string
    {
        return static::class;
    }

    /**
     * Метод для возврата названия
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

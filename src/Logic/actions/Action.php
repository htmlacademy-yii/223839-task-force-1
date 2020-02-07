<?php

namespace src\Logic\actions;


abstract class Action
{
    /**
     * метод для возврата названия
     * @return string
     */
    abstract function getPublicName();

    /**
     * метод для возврата имени класса без namespace
     * @return string
     */
    public static function getInnerName()
    {
        $class = explode('\\', static::class);
        return $class[count($class) - 1];
    }

    /**
     * Метод возвращает true или false в зависимости от доступности выполнения этого действия
     *
     * @param $customerID
     * @param $performerID
     * @param $currentUserID
     *
     * @return bool
     */
    abstract protected function checkRights($customerID, $performerID, $currentUserID);
}

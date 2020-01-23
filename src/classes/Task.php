<?php

/**
 *  Класс определяет списки действий и статусов, а также выполняет базовую работу с ними.
 *
 */

//interface iRoleUsers
//{
//    const ROLE_GUEST = 'guest'; // гость;
//    const ROLE_CUSTOMER = 'client'; // заказчик
//    const ROLE_PERFORMER = 'performer'; // исполнитель
//}

class Task
{

    const ROLE_GUEST = 'guest'; // гость;
    const ROLE_CUSTOMER = 'client'; // заказчик
    const ROLE_PERFORMER = 'performer'; // исполнитель

    const ACTION_START = 'starting'; // начать
    const ACTION_REFUSAL = 'refused'; //  отказаться
    const ACTION_PERFORMED = 'performed'; // на выполнении
    const ACTION_COMPLETE = 'completed'; // выполнено
    const ACTION_CANCELED = 'canceled'; // провалено

    const STATUS_NEW = 'new'; // новые
    const STATUS_CANCELED = 'canceled'; // отмененные
    const STATUS_ACTIVE = 'active'; // на исполнении
    const STATUS_COMPLETED = 'completed'; // выполненные
    const STATUS_FAILED = 'failed'; // проваленные
    const STATUS_ALL = 'all'; // все статусы

    public $orderInfo = [];

    private $customerID;
    private $performerID;
    private $currentStatus;
    static $allowActive = [];

    public function __construct($customerID, $performerID, $status)
    {
        $this->customerID = $customerID;
        $this->performerID = $performerID;
        $this->currentStatus = self::STATUS_NEW;
    }

    /**
     *  получить константы класса
     */
    static function getConst()
    {

    }

    /**
     *  получить все статусы
     */
    static function getAllStatus()
    {

    }

    /**
     *  получить все доступные действия
     */
    static function getAllowActive()
    {

    }

}

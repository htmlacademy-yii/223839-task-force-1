<?php

namespace Logic;

use src\Logic\actions\ActionCancel;
use src\Logic\actions\ActionComplete;
use src\Logic\actions\ActionRefusal;
use src\Logic\actions\ActionStart;

/**
 * Класс определяет списки действий и статусов, а также выполняет базовую работу с ними.
 * Class Task
 * @package Logic
 */
class Task
{
    const ACTION_START = 0;
    const ACTION_REFUSAL = 1;
    const ACTION_COMPLETE = 2;
    const ACTION_CANCEL = 3;

    const STATUS_NEW = 0;
    const STATUS_CANCELED = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_FAILED = 4;

    public $customerID;
    public $performerID;

    /**
     * Task constructor.
     *
     * @param $customerID
     * @param $performerID
     */
    public function __construct($customerID, $performerID)
    {
        $this->customerID = $customerID;
        $this->performerID = $performerID;
    }

    /**
     * метод возвращает массив всех статусов
     * @return array
     */
    public static function getAllStatuses()
    {
        return [
            self::STATUS_NEW => 'Новый',
            self::STATUS_CANCELED => 'Отмененный',
            self::STATUS_ACTIVE => 'Действующий',
            self::STATUS_COMPLETED => 'Завершенный',
            self::STATUS_FAILED => 'Проваленный'
        ];
    }

    /**
     * метод возвращает массив всех действий
     * @return array
     */
    public static function getAllActions() {
        return [
            self::ACTION_START => 'Начать',
            self::ACTION_CANCEL => 'Отменить',
            self::ACTION_COMPLETE => 'Завершить',
            self::ACTION_REFUSAL => 'Отказаться'
        ];
    }

    /**
     * Метод возвращает массив доступных действий для указанного статуса
     * @param $status
     * @return array
     */
    public function getActionForStatus( $status )
    {
        switch ( $status )
        {
            case self::STATUS_NEW:
                return [
                    new ActionStart(),
                    new ActionCancel()
                ];
            case self::STATUS_ACTIVE:
                return [
                    new ActionComplete(),
                    new ActionRefusal()
                ];
        }
        return null;
    }

    /**
     * метод для получения статуса, в которой он перейдёт после выполнения указанного действия
     * @param $action
     * @return int
     */
    public function getNextStatus( $action )
    {
        switch ( $action )
        {
            case ActionStart::getInnerName():
                return self::STATUS_ACTIVE;
            case ActionCancel::getInnerName():
                return self::STATUS_CANCELED;
            case ActionComplete::getInnerName():
                return self::STATUS_COMPLETED;
            case ActionRefusal::getInnerName():
                return self::STATUS_FAILED;
        }
        return null;
    }
}

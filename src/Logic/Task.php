<?php

namespace Logic;

use src\Logic\actions\{Action, ActionStart, ActionRefusal, ActionComplete, ActionCancel};
use src\error\ErrorHandler;



/**
 * Класс определяет списки действий и статусов, а также выполняет базовую работу с ними.
 * Class Task
 * @package Logic
 */
class Task
{
    const ACTION_START = 1;
    const ACTION_REFUSAL = 2;
    const ACTION_COMPLETE = 3;
    const ACTION_CANCEL = 4;

    const STATUS_NEW = 1;
    const STATUS_CANCELED = 2;
    const STATUS_ACTIVE = 3;
    const STATUS_COMPLETED = 4;
    const STATUS_FAILED = 5;

    protected $customerID;
    protected $performerID;

    /**
     * Task constructor.
     *
     * @param int $customerID
     * @param int $performerID
     */
    public function __construct(int $customerID, int $performerID)
    {
        $this->customerID = $customerID;
        $this->performerID = $performerID;
    }

    /**
     * метод возвращает массив всех статусов
     * @return array
     */
    public static function getAllStatuses() : array
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
    public static function getAllActions() : array
    {
        return [
            self::ACTION_START => new ActionStart(),
            self::ACTION_CANCEL => new ActionCancel(),
            self::ACTION_COMPLETE => new ActionComplete(),
            self::ACTION_REFUSAL => new ActionRefusal()
        ];
    }

    /**
     * Метод возвращает массив с объектами доступных действий для указанного статуса
     *
     * @param int $status
     * @return Action[]
     */
    public function getActionForStatus( int $status ) : array
    {
        try {
            if (!array_key_exists($status, self::getAllStatuses())) {
                throw new ErrorHandler($status . ' статус отсутствует');
            }
        } catch (ErrorHandler $e) {
            echo $e->getMessage() .'<br>';
        }
        switch ( $status ) {
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
     * @param Action $action
     *
     * @return int|null
     */
    public function getNextStatus( Action $action ) : ?int
    {
        try {
            if (!in_array($action, self::getAllActions())) {
                throw new ErrorHandler($action::getInnerName() . ' действие отсутствует');
            }
        } catch (ErrorHandler $e) {
            echo $e->getMessage() .'<br>';
        }
        switch ( $action )
        {
            case new ActionStart():
                return self::STATUS_ACTIVE;
            case new ActionCancel;
                return self::STATUS_CANCELED;
            case new ActionComplete():
                return self::STATUS_COMPLETED;
            case new ActionRefusal():
                return self::STATUS_FAILED;
        }
        return null;
    }
}

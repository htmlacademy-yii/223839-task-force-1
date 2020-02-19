<?php

namespace Logic;

use src\error\ActionException;
use src\error\baseException;
use src\Logic\actions\{Action, ActionCancel, ActionComplete, ActionRefusal, ActionStart};
use src\error\TaskException;


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
    const ACTIONS_NAMES = [
        ActionStart::class,
        ActionCancel::class,
        ActionComplete::class,
        ActionRefusal::class
    ];

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
        $this->customerID  = $customerID;
        $this->performerID = $performerID;
    }

    /**
     * Метод возвращает массив с объектами доступных действий для указанного статуса
     *
     * @param int $status
     *
     * @return Action[]
     * @throws baseException
     */
    public function getActionForStatus(int $status): array
    {
        if ( ! array_key_exists($status, Task::getAllStatuses())) {
            throw new TaskException('Статус не существует', __FILE__, __LINE__, $status);
        }
        if ($status != self::STATUS_NEW && $status != self::STATUS_ACTIVE) {
            throw new TaskException('У статуса нет действий', __FILE__, __LINE__, $status);
        }
        switch ($status) {
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
     * метод возвращает массив всех статусов
     * @return array
     */
    public static function getAllStatuses(): array
    {
        return [
            self::STATUS_NEW       => 'Новый',
            self::STATUS_CANCELED  => 'Отмененный',
            self::STATUS_ACTIVE    => 'Действующий',
            self::STATUS_COMPLETED => 'Завершенный',
            self::STATUS_FAILED    => 'Проваленный'
        ];
    }

    /**
     * метод для получения статуса, в которой он перейдёт после выполнения указанного действия
     *
     * @param Action $action
     *
     * @return int|null
     * @throws baseException
     */
    public function getNextStatus(Action $action): ?int
    {
        $action = get_class($action);
        if ( ! in_array($action, self::ACTIONS_NAMES,true)) {
            throw new ActionException(' действие отсутствует', __FILE__, __LINE__, $action);
        }
        switch ($action) {
            case ActionStart::class:
                return self::STATUS_ACTIVE;
            case ActionCancel::class:
                return self::STATUS_CANCELED;
            case ActionComplete::class:
                return self::STATUS_COMPLETED;
            case ActionRefusal::class:
                return self::STATUS_FAILED;
        }

        return null;
    }

    /**
     * метод возвращает массив всех действий
     * @return array
     */
    public static function getAllActions(): array
    {
        return [
            self::ACTION_START    => 'начать',
            self::ACTION_CANCEL   => 'отменить',
            self::ACTION_COMPLETE => 'завершить',
            self::ACTION_REFUSAL  => 'отказаться'
        ];
    }
}

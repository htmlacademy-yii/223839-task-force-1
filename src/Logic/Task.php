<?php

namespace Logic;

use src\Logic\actions\{Action, ActionCancel, ActionComplete, ActionRefusal, ActionStart};
use src\error\ActionNotExistException;
use src\error\TaskStatusNotExistException;
use src\error\TaskStatusNotHasActionsException;

/**
 * Класс определяет списки действий и статусов, а также выполняет базовую работу с ними
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
     * @throws TaskStatusNotExistException Статуса не существует
     * @throws TaskStatusNotHasActionsException Статус существует, но для него нет доступных действий
     */
    public function getActionForStatus(int $status): array
    {
        if ( ! array_key_exists($status, Task::getAllStatuses())) {
            throw new TaskStatusNotExistException('Status not exist');
        }
        if ($status != self::STATUS_NEW && $status != self::STATUS_ACTIVE) {
            throw new TaskStatusNotHasActionsException('Status don\'t have actions');
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
            default:
                return null;
        }
    }

    /**
     * Метод возвращает массив всех статусов
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
     * Метод для получения статуса, в которой он перейдёт после выполнения указанного действия
     *
     * @param Action $action
     *
     * @return int|null
     * @throws ActionNotExistException Действие не существует
     */
    public function getNextStatus(Action $action): ?int
    {
        $action = get_class($action);
        if ( ! in_array($action, self::ACTIONS_NAMES, true)) {
            throw new ActionNotExistException('Action not exist');
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
            default:
                return null;
        }

    }

    /**
     * Метод возвращает массив всех действий
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

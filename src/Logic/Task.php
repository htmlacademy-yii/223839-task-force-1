<?php

namespace Logic;

use Logic\Actions\{TaskAction, TaskActionCancel, TaskActionComplete, TaskActionRefusal, TaskActionStart};
use Exceptions\ActionNotExistException;
use Exceptions\TaskStatusNotExistException;
use Exceptions\TaskStatusNotHasActionsException;

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
        TaskActionStart::class,
        TaskActionCancel::class,
        TaskActionComplete::class,
        TaskActionRefusal::class
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
        $this->customerID = $customerID;
        $this->performerID = $performerID;
    }

    /**
     * Метод возвращает массив с объектами доступных действий для указанного статуса
     *
     * @param int $status
     *
     * @return TaskAction[]
     * @throws TaskStatusNotExistException Статус не существует
     * @throws TaskStatusNotHasActionsException Статус существует, но для него нет доступных действий
     */
    public function getActionForStatus(int $status): ?array
    {
        if (!array_key_exists($status, Task::getAllStatuses())) {
            throw new TaskStatusNotExistException();
        }
        if ($status != self::STATUS_NEW && $status != self::STATUS_ACTIVE) {
            throw new TaskStatusNotHasActionsException();
        }
        switch ($status) {
            case self::STATUS_NEW:
                return [
                    new TaskActionStart(),
                    new TaskActionCancel()
                ];
            case self::STATUS_ACTIVE:
                return [
                    new TaskActionComplete(),
                    new TaskActionRefusal()
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
            self::STATUS_NEW => 'Новый',
            self::STATUS_CANCELED => 'Отмененный',
            self::STATUS_ACTIVE => 'Действующий',
            self::STATUS_COMPLETED => 'Завершенный',
            self::STATUS_FAILED => 'Проваленный'
        ];
    }

    /**
     * Метод для получения статуса, в которой он перейдёт после выполнения указанного действия
     *
     * @param TaskAction $action
     *
     * @return int|null
     * @throws ActionNotExistException Действие не существует
     */
    public function getNextStatus(TaskAction $action): ?int
    {
        $action = get_class($action);
        if (!in_array($action, self::ACTIONS_NAMES, true)) {
            throw new ActionNotExistException("{$action} line ".__LINE__." ".__METHOD__);
        }

        switch ($action) {
            case TaskActionStart::class:
                return self::STATUS_ACTIVE;
            case TaskActionCancel::class:
                return self::STATUS_CANCELED;
            case TaskActionComplete::class:
                return self::STATUS_COMPLETED;
            case TaskActionRefusal::class:
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
            self::ACTION_START => 'начать',
            self::ACTION_CANCEL => 'отменить',
            self::ACTION_COMPLETE => 'завершить',
            self::ACTION_REFUSAL => 'отказаться'
        ];
    }
}

<?php


namespace src\tests;

use Logic\Task;
use src\Logic\actions\{ActionStart, ActionRefusal, ActionComplete, ActionCancel};


class Templates
{

    public static function getTask($customerID, $performerID)
    {
        return new Task($customerID,$performerID);
    }

    /*
   * присутствует ли Action в массиве разрешенных действий для данного статуса
   */
    public static function templateIsHasActions(int $customerID, int $performerID,  $action, int $status)
    {
        $task = self::getTask($customerID, $performerID);
        return assert(in_array($action, $task->getActionForStatus($status)),  'Action не разрешен');
    }

    /**
     * проверка на правильность статуса после действия
     * @param string $action
     * @param int $status
     * @param int $customerID
     * @param int $performerID
     *
     * @return bool
     */
    public static function templateAfterAction(string $action, int $status, int $customerID, int $performerID)
    {
        $task = self::getTask($customerID, $performerID);
        return assert($task->getNextStatus($action) === $status,
            $task->getNextStatus($action) . ' != ' .  $status . ' | ' . $action . ' действие не выполнено');
    }

    /*
    * сверяет идентичность объектов в массиве
    */
    public static function templateRightActionsForStatus(int $customerID, int $performerID, int $status, array $actions)
    {
        $task = self::getTask($customerID, $performerID);
        return assert($task->getActionForStatus($status) == $actions);
    }

    /*
     *  проверка прав пользователя для данного действия
     */
    public static function templateActionCheckRight(int $currentUserID, int $customerID, int $performerID, $actionObj)
    {
        $action = $actionObj->checkRights($customerID, $performerID, $currentUserID);
        return assert($action, $actionObj::getInnerName() . ' действие не доступно');
    }

}

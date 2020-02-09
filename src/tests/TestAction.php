<?php


namespace src\tests;

use Logic\Task;
use src\Logic\actions\{ActionStart, ActionRefusal, ActionComplete, ActionCancel};


class TestAction extends Templates
{
    /*
    * присутствует ли Action в массиве разрешенных действий для данного статуса
    */
    public static function templateIsHasActions(int $customerID, int $performerID,  $action, int $status)
    {
        $task = self::getTask($customerID, $performerID);
        return assert(in_array($action, $task->getActionForStatus($status)),  'Action не разрешен');
    }

    public function testCompleteAction()
    {
        $task = new Task(2,2);
        $action = new ActionComplete();
        $action = $action->checkRights($task->customerID,$task->performerID, 1);
        return assert($action, ActionComplete::getInnerName() . ' действие не доступно');
    }

    public function testStartAction()
    {
        $task = new Task(1,2);
        $action = new ActionStart();
        $action = $action->checkRights($task->customerID,$task->performerID, 2);
        return assert($action, ActionStart::getInnerName() . ' действие не доступно');
    }

    public function testIsHasStart()
    {
        $action = new ActionStart();
        $test = self::templateIsHasActions(1,2, $action, Task::STATUS_NEW);
        return $test;
    }

    public function testIsHasCancel()
    {
        $action = new ActionCancel();
        $test = self::templateIsHasActions(1,2, $action, Task::STATUS_NEW);
        return $test;
    }

    public function testIsHasComplete()
    {
        $action = new ActionComplete();
        $test = self::templateIsHasActions(1,2, $action, Task::STATUS_ACTIVE);
        return $test;
    }

    public function testIsHasRefusal()
    {
        $action = new ActionComplete();
        $test = self::templateIsHasActions(1,2, $action, Task::STATUS_ACTIVE);
        return $test;
    }
}

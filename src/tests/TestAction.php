<?php


namespace src\tests;

use Logic\Task;
use src\Logic\actions\{ActionStart, ActionRefusal, ActionComplete, ActionCancel};


class TestAction
{

    public function testCompleteAction()
    {
        $task = new Task(1,2);
        $action = new ActionComplete();
        $action = $action->checkRights($task->customerID,$task->performerID, 2);
        assert($action, ActionComplete::getInnerName() . ' действие не доступно');
    }
    public function testStartAction()
    {
        $task = new Task(1,2);
        $action = new ActionStart();
        $action = $action->checkRights($task->customerID,$task->performerID, 2);
        assert($action, ActionStart::getInnerName() . ' действие не доступно');
    }
}

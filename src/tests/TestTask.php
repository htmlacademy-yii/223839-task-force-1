<?php


namespace src\tests;

use Logic\Task;
use src\Logic\actions\{ActionStart, ActionRefusal, ActionComplete, ActionCancel};


class TestTask
{
    public function testRightActionsForNew()
    {
        $task = new Task(1, 2);
        $status = Task::STATUS_NEW;
        $action1 = new ActionStart();
        $action2 = new ActionCancel();
        $actions = [$action1, $action2];
        return assert($task->getActionForStatus($status) == $actions, 'идентичность объектов в массиве');
    }

    public function testRightActionsForActive()
    {
        $task = new Task(1, 2);
        $status = Task::STATUS_ACTIVE;
        $action1 = new ActionComplete();
        $action2 = new ActionRefusal();
        $actions = [$action1, $action2];
        return assert($task->getActionForStatus($status) == $actions, 'идентичность объектов в массиве');
    }
}


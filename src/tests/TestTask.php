<?php


namespace src\tests;

use Logic\Task;
use src\Logic\actions\{TaskActionStart, TaskActionRefusal, TaskActionComplete, TaskActionCancel};


class TestTask
{
    public function testRightActionsForNew()
    {
        $task = new Task(1, 2);
        $status = Task::STATUS_NEW;
        $action1 = new TaskActionStart();
        $action2 = new TaskActionCancel();
        $actions = [$action1, $action2];
        return assert($task->getActionForStatus($status) == $actions, 'идентичность объектов в массиве');
    }

    public function testRightActionsForActive()
    {
        $task = new Task(1, 2);
        $status = Task::STATUS_ACTIVE;
        $action1 = new TaskActionComplete();
        $action2 = new TaskActionRefusal();
        $actions = [$action1, $action2];
        return assert($task->getActionForStatus($status) == $actions, 'идентичность объектов в массиве');
    }
}


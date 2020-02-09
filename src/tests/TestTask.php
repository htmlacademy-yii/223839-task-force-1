<?php


namespace src\tests;

use Logic\Task;
use src\Logic\actions\ActionCancel;
use src\Logic\actions\ActionComplete;
use src\Logic\actions\ActionRefusal;
use src\Logic\actions\ActionStart;

class TestTask
{

    public function testStatusAfterStar1t()
    {
        assert(1==1, 'один не равен одному');
    }

    public function testStatusAfterStart()
    {
        $task = new Task(1,2);
        assert($task->getNextStatus(ActionComplete::getInnerName() === Task::STATUS_ACTIVE),
            $task->getNextStatus(ActionStart::getInnerName()) . ' != ' .  Task::STATUS_ACTIVE);
        //        assert($task->newInstance()->getNextStatus(ActionStart::getInnerName()) === Task::STATUS_ACTIVE);
    }
    public function testStatusAfterComplete()
    {
        $task = new Task(1,2);
        assert($task->getNextStatus(ActionComplete::getInnerName() === Task::STATUS_COMPLETED),
            $task->getNextStatus(ActionComplete::getInnerName()) . ' != ' .  Task::STATUS_COMPLETED);
        //        assert($task->newInstance()->getNextStatus(ActionStart::getInnerName()) === Task::STATUS_ACTIVE);
    }
}


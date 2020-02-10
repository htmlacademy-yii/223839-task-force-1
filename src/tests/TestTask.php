<?php


namespace src\tests;

use Logic\Task;
use src\Logic\actions\{Action, ActionStart, ActionRefusal, ActionComplete, ActionCancel};


class TestTask extends Templates
{
    public function testRightActionsForNew()
    {
        $start = new ActionStart();
        $cancel = new ActionCancel();
        $test = self::templateRightActionsForStatus(1,2,Task::STATUS_NEW, [$start, $cancel]);
        return $test;
    }

    public function testRightActionsForActive()
    {
        $complete = new ActionComplete();
        $refusal = new ActionRefusal();
        $test = self::templateRightActionsForStatus(1,2,Task::STATUS_ACTIVE, [$complete, $refusal]);
        return $test;
    }
}


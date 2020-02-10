<?php


namespace src\tests;


use Logic\Task;
use src\Logic\actions\ActionRefusal;

class TestActionRefusal extends Templates
{
    public function testIsHasRefusal()
    {
        $action = new ActionRefusal();
        $test = self::templateIsHasActions(1,2, $action, Task::STATUS_ACTIVE);
        return $test;
    }

    public function testStatusAfterRefusal()
    {
        $test = self::templateAfterAction(ActionRefusal::getInnerName(), Task::STATUS_FAILED, 1,1);
        return $test;
    }

    public function testStartActionCheckRight()
    {
        $action = new ActionRefusal();
        $test = self::templateActionCheckRight(2, 1,2, $action);
        return $test;
    }
}

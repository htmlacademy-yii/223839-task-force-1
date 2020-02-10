<?php


namespace src\tests;


use Logic\Task;
use src\Logic\actions\ActionComplete;

class TestActionComplete extends Templates
{
    public function testStartActionCheckRight()
    {
        $action = new ActionComplete();
        $test = self::templateActionCheckRight(1, 1,2, $action);
        return $test;
    }

    public function testIsHasComplete()
    {
        $action = new ActionComplete();
        $test = self::templateIsHasActions(1,2, $action, Task::STATUS_ACTIVE);
        return $test;
    }

    public function testStatusAfterComplete()
    {
        $test = self::templateAfterAction(ActionComplete::getInnerName(), Task::STATUS_COMPLETED, 1,1);
        return $test;
    }
}

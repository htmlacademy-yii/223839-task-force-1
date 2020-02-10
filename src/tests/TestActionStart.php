<?php


namespace src\tests;


use Logic\Task;
use src\Logic\actions\ActionStart;

class TestActionStart extends Templates
{

    public function testIsHasStart()
    {
        $action = new ActionStart();
        $test = self::templateIsHasActions(1,2, $action, Task::STATUS_NEW);
        return $test;
    }

    public function testStatusAfterStart()
    {
        $test = self::templateAfterAction(ActionStart::getInnerName(), Task::STATUS_ACTIVE, 1,1);
        return $test;
    }

    public function testStartActionCheckRight()
    {
        $action = new ActionStart();
        $test = self::templateActionCheckRight(1, 2,1, $action);
        return $test;
    }

}

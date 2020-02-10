<?php


namespace src\tests;


use Logic\Task;
use src\Logic\actions\ActionCancel;

class TestActionCancel extends Templates
{
    public function testIsHasCancel()
    {
        $action = new ActionCancel();
        $test = self::templateIsHasActions(1,2, $action, Task::STATUS_NEW);
        return $test;
    }

    public function testStatusAfterCancel()
    {
        $test = self::templateAfterAction(ActionCancel::getInnerName(), Task::STATUS_CANCELED, 1,1);
        return $test;
    }

    public function testStartActionCheckRight()
    {
        $action = new ActionCancel();
        $test = self::templateActionCheckRight(1, 1,2, $action);
        return $test;
    }
}

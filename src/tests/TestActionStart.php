<?php


namespace src\tests;


use Logic\Task;
use src\Logic\actions\ActionStart;

class TestActionStart
{
    public static function getTask(int $customerID, int $performerID)
    {
        return new Task($customerID,$performerID);
    }

    public function testIsHasStart() : bool
    {
        $task = self::getTask(1,2);
        $action = new ActionStart();
        return assert(in_array($action, $task->getActionForStatus(Task::STATUS_NEW)),  'Action не разрешен');
    }

    public function testStatusAfterStart() : bool
    {
        $task = self::getTask(1,2);
        $action = new ActionStart();
        $status = Task::STATUS_ACTIVE;
        return assert($task->getNextStatus($action) === $status,
            $task->getNextStatus($action) . ' != ' .  $status . ' |  статус после выполнения ' . $action::getInnerName()
            . ' не соответствует этому действию' );
    }

    public function testStartActionCheckRight() : bool
    {
        $action = new ActionStart();
        $test = $action->checkRights(1, 2, 2);
        return assert($test, $action::getInnerName() . ' действие не доступно для пользователя');
    }

}

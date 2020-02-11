<?php


namespace src\tests;


use Logic\Task;
use src\Logic\actions\ActionStart;

class TestActionStart
{
    public static function getTask($customerID, $performerID)
    {
        return new Task($customerID,$performerID);
    }

    public function testIsHasStart()
    {
        $task = self::getTask(1,2);
        $action = new ActionStart();
        return assert(in_array($action, $task->getActionForStatus(Task::STATUS_NEW)),  'Action не разрешен');
    }

    public function testStatusAfterStart()
    {
        $task = self::getTask(1,2);
        $action = ActionStart::getInnerName();
        $status = Task::STATUS_ACTIVE;
        return assert($task->getNextStatus($action) === $status,
            $task->getNextStatus($action) . ' != ' .  $status . ' |  статус после выполнения ' . $action
            . ' не соотвутствует этому действию' );
    }

    public function testStartActionCheckRight()
    {
        $action = new ActionStart();
        $test = $action->checkRights(1, 2, 2);
        return assert($test, $action::getInnerName() . ' действие не доступно для пользователя');
    }

}

<?php


namespace src\tests;


use Logic\Task;
use src\Logic\actions\ActionCancel;

class TestActionCancel
{
    public static function getTask($customerID, $performerID)
    {
        return new Task($customerID,$performerID);
    }

    public function testIsHasCancel()
    {
        $task = self::getTask(1,2);
        $action = new ActionCancel();
        return assert(in_array($action, $task->getActionForStatus(Task::STATUS_NEW)),  'Action не разрешен');
    }

    public function testStatusAfterCancel()
    {
        $task = self::getTask(1,2);
        $action = ActionCancel::getInnerName();
        $status = Task::STATUS_CANCELED;
        return assert($task->getNextStatus($action) === $status,
            $task->getNextStatus($action) . ' != ' .  $status . ' |  статус после выполнения ' . $action
            . ' не соотвутствует этому действию' );
    }

    public function testStartActionCheckRight()
    {
        $action = new ActionCancel();
        $test = $action->checkRights(1, 2, 1);
        return assert($test, $action::getInnerName() . ' действие не доступно для пользователя');
    }
}

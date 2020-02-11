<?php


namespace src\tests;


use Logic\Task;
use src\Logic\actions\ActionComplete;

class TestActionComplete
{
    public static function getTask($customerID, $performerID)
    {
        return new Task($customerID,$performerID);
    }

    public function testStartActionCheckRight()
    {
        $task = self::getTask(1,2);
        $action = new ActionComplete();
        return assert(in_array($action, $task->getActionForStatus(Task::STATUS_ACTIVE)),  'Action не разрешен');
    }

    public function testIsHasComplete()
    {
        $task = self::getTask(1,2);
        $action = ActionComplete::getInnerName();
        $status = Task::STATUS_COMPLETED;
        return assert($task->getNextStatus($action) === $status,
            $task->getNextStatus($action) . ' != ' .  $status . ' |  статус после выполнения ' . $action
            . ' не соотвутствует этому действию' );
    }

    public function testStatusAfterComplete()
    {
        $action = new ActionComplete();
        $test = $action->checkRights(1, 2, 1);
        return assert($test, $action::getInnerName() . ' действие не доступно для пользователя');
    }
}

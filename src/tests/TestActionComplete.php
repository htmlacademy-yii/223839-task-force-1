<?php


namespace src\tests;


use Logic\Task;
use src\Logic\actions\ActionComplete;

class TestActionComplete
{
    public function testStartActionCheckRight() : bool
    {
        $task = new Task(1,2);
        $action = new ActionComplete();
        return assert(in_array($action, $task->getActionForStatus(Task::STATUS_ACTIVE)),  'Action не разрешен');
    }

    public function testIsHasComplete() : bool
    {
        $task = new Task(1,2);
        $action = new ActionComplete();
        $status = Task::STATUS_COMPLETED;
        return assert($task->getNextStatus($action) === $status,
            $task->getNextStatus($action) . ' != ' .  $status . ' |  статус после выполнения ' . $action::getInnerName()
            . ' не соответствует этому действию' );
    }

    public function testStatusAfterComplete() : bool
    {
        $action = new ActionComplete();
        $test = $action->checkRights(1, 2, 1);
        return assert($test, $action::getInnerName() . ' действие не доступно для пользователя');
    }
}

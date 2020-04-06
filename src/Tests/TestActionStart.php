<?php


namespace Tests;


use Logic\Task;
use Logic\Actions\TaskActionStart;

class TestActionStart
{
    public function testIsHasStart() : bool
    {
        $task = new Task(1,2);
        $action = new TaskActionStart();
        return assert(in_array($action, $task->getActionForStatus(Task::STATUS_NEW)),  'TaskAction не разрешен');
    }

    public function testStatusAfterStart() : bool
    {
        $task = new Task(1,2);
        $action = new TaskActionStart();
        $status = Task::STATUS_ACTIVE;
        return assert($task->getNextStatus($action) === $status,
            $task->getNextStatus($action) . ' != ' .  $status . ' |  статус после выполнения ' . $action::getInnerName()
            . ' не соответствует этому действию' );
    }

    public function testStartActionCheckRight() : bool
    {
        $action = new TaskActionStart();
        $test = $action->checkRights(1, 2, 2);
        return assert($test, $action::getInnerName() . ' действие не доступно для пользователя ');
    }

}

<?php

namespace src\tests;

use Logic\Task;
use src\Logic\actions\TaskActionCancel;

class TestActionCancel
{
    public function testIsHasCancel(): bool
    {
        $task = new Task(1, 2);
        $action = new TaskActionCancel();
        return assert(in_array($action, $task->getActionForStatus(Task::STATUS_NEW)), 'TaskAction не разрешен');
    }

    public function testStatusAfterCancel(): bool
    {
        $task = new Task(1, 2);
        $action = new TaskActionCancel();
        $status = Task::STATUS_CANCELED;
        return assert($task->getNextStatus($action) === $status,
            $task->getNextStatus($action) . ' != ' . $status . ' |  статус после выполнения ' . $action::getInnerName()
            . ' не соотвествует этому действию');
    }

    public function testStartActionCheckRight(): bool
    {
        $action = new TaskActionCancel();
        $test = $action->checkRights(1, 2, 1);
        return assert($test, $action::getInnerName() . ' действие не доступно для пользователя');
    }
}

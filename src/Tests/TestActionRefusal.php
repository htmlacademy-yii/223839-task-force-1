<?php

namespace Tests;

use Logic\Task;
use Logic\Actions\TaskActionRefusal;

class TestActionRefusal
{
    public function testIsHasRefusal(): bool
    {
        $task = new Task(1, 2);
        $action = new TaskActionRefusal();
        return assert(in_array($action, $task->getActionForStatus(Task::STATUS_ACTIVE)), 'TaskAction не разрешен');
    }

    public function testStatusAfterRefusal(): bool
    {
        $task = new Task(1, 2);
        $action = new TaskActionRefusal();
        $status = Task::STATUS_FAILED;
        return assert($task->getNextStatus($action) === $status,
            $task->getNextStatus($action) . ' != ' . $status . ' |  статус после выполнения ' . $action::getInnerName()
            . ' не соответствует этому действию');
    }

    public function testStartActionCheckRight(): bool
    {
        $action = new TaskActionRefusal();
        $test = $action->checkRights(1, 2, 2);
        return assert($test, $action::getInnerName() . ' действие не доступно для пользователя');
    }
}

<?php


namespace src\tests;

use Logic\Task;
use src\Logic\actions\{Action, ActionStart, ActionRefusal, ActionComplete, ActionCancel};


class TestTask extends Templates
{
    /**
     * проверка на правильность статуса после действия
     * @param string $action
     * @param int $status
     * @param int $customerID
     * @param int $performerID
     *
     * @return bool
     */
    public static function templateAfterAction(string $action, int $status, int $customerID, int $performerID)
    {
        $task = self::getTask($customerID, $performerID);
        return assert($task->getNextStatus($action) === $status,
            $task->getNextStatus($action) . ' != ' .  $status . ' | ' . $action . ' действие не выполнено');
    }

    /*
     * сверяет идентичность объектов в массиве
     */
    public static function templateRightActionsForStatus(int $customerID, int $performerID, int $status, array $actions)
    {
        $task = self::getTask($customerID, $performerID);
        return assert($task->getActionForStatus($status) == $actions);
    }
    /////////////////////////////////////////////////////////////////////////////////////////

    public function testStatusAfterStart()
    {
        $test = self::templateAfterAction(ActionStart::getInnerName(), Task::STATUS_ACTIVE, 1,1);
        return $test;
    }

    public function testStatusAfterComplete()
    {
        $test = self::templateAfterAction(ActionComplete::getInnerName(), Task::STATUS_COMPLETED, 1,1);
        return $test;
    }

    public function testStatusAfterCancel()
    {
        $test = self::templateAfterAction(ActionCancel::getInnerName(), Task::STATUS_CANCELED, 1,1);
        return $test;
    }

    public function testStatusAfterRefusal()
    {
        $test = self::templateAfterAction(ActionRefusal::getInnerName(), Task::STATUS_FAILED, 1,1);
        return $test;
    }

    public function testRightActionsForNew()
    {
        $start = new ActionStart();
        $cancel = new ActionCancel();
        $test = self::templateRightActionsForStatus(1,2,Task::STATUS_NEW, [$start, $cancel]);
        return $test;
    }

    public function testRightActionsForActive()
    {
        $complete = new ActionComplete();
        $refusal = new ActionRefusal();
        $test = self::templateRightActionsForStatus(1,2,Task::STATUS_ACTIVE, [$complete, $refusal]);
        return $test;
    }

}


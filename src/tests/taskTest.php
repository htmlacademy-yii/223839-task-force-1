<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once  $root . '/vendor/autoload.php';

use Logic\Task;
use src\Logic\actions\ActionCancel;
use src\Logic\actions\ActionComplete;
use src\Logic\actions\ActionRefusal;
use src\Logic\actions\ActionStart;

$testTask = new Task(1, 2);
$testActionComplete = new ActionComplete();
$testActionCancel   = new ActionCancel();
$testActionRefusal  = new ActionRefusal();
$testActionStart    = new ActionStart();

//  равен ли статус после выполнения действия ожидаемому статусу
assert($testTask->getNextStatus($testActionStart::getInnerName()) === Task::STATUS_ACTIVE);
assert($testTask->getNextStatus($testActionRefusal::getInnerName()) === Task::STATUS_FAILED);
assert($testTask->getNextStatus($testActionCancel::getInnerName()) === Task::STATUS_CANCELED);
assert($testTask->getNextStatus($testActionComplete::getInnerName()) === Task::STATUS_COMPLETED);

// проверка идентичности объектов action в массиве разрешенных действий
assert($testTask->getActionForStatus(Task::STATUS_NEW) == [$testActionStart, $testActionCancel]);
assert($testTask->getActionForStatus(Task::STATUS_ACTIVE) == [$testActionComplete, $testActionRefusal]);

// присутствует ли Action в массиве разрешенных действий для данного статуса
assert(in_array($testActionCancel, $testTask->getActionForStatus(Task::STATUS_NEW)));
assert(in_array($testActionStart, $testTask->getActionForStatus(Task::STATUS_NEW)));
assert(in_array($testActionComplete, $testTask->getActionForStatus(Task::STATUS_ACTIVE)));
assert(in_array($testActionRefusal, $testTask->getActionForStatus(Task::STATUS_ACTIVE)));

// проверка прав пользователя для данного действия
assert($testActionStart->checkRights($testTask->customerID, $testTask->performerID, 2));
assert($testActionCancel->checkRights($testTask->customerID, $testTask->performerID, 1));
assert($testActionRefusal->checkRights($testTask->customerID, $testTask->performerID, 2));
assert($testActionComplete->checkRights($testTask->customerID, $testTask->performerID, 1));

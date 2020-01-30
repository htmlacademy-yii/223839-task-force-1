<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once  $root . '/vendor/autoload.php';

use src\Logic\Task;

$test = new Task(169,329);

assert($test->getNextStatus(Task::ACTION_START) === Task::STATUS_ACTIVE);
assert($test->getNextStatus(Task::ACTION_REFUSAL) === Task::STATUS_FAILED);
assert($test->getNextStatus(Task::ACTION_CANCEL) === Task::STATUS_CANCELED);
assert($test->getNextStatus(Task::ACTION_COMPLETE) === Task::STATUS_COMPLETED);

assert($test->getActionForStatus(Task::STATUS_NEW) === [Task::ACTION_START, Task::ACTION_CANCEL]);
assert($test->getActionForStatus(Task::STATUS_ACTIVE) === [Task::ACTION_COMPLETE, Task::ACTION_REFUSAL]);


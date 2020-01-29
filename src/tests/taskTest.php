<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once  $root . '/vendor/autoload.php';

use src\Logic\Task;

$test = new Task(169,329);

assert($test->getActionForStatus(Task::STATUS_NEW) === $test->getActionForStatus(Task::STATUS_NEW));
assert($test->getActionForStatus(Task::STATUS_ACTIVE) === $test->getActionForStatus(Task::STATUS_ACTIVE));


<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once  $root . '/vendor/autoload.php';

use Logic\Task;


$test = new Task('1695','329');
$test->currentStatus = Task::STATUS_ACTIVE;
$test->action = Task::ACTION_CANCEL;

assert($test->getNextStatus());



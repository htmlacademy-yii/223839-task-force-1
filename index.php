<?php
use src\classes;

require_once 'vendor/autoload.php';

$task = new classes\Task(2,3, classes\Task::ACTION_START);   echo '<br>';
//$task = new classes\Task(2,3, classes\Task::ACTION_REFUSAL);      echo '<br>';
$task = new classes\Task(2,3, classes\Task::ACTION_PERFORM);      echo '<br>';
//$task = new classes\Task(2,3, classes\Task::ACTION_COMPLETE);      echo '<br>';
//$task = new classes\Task(2,3, classes\Task::ACTION_CANCEL);      echo '<br>';

assert_options(ASSERT_ACTIVE, 1);
assert(classes\Task::ACTION_START == classes\Task::STATUS_COMPLETED,'');

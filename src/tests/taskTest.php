<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once  $root . '/vendor/autoload.php';

use Logic\Task;

$test = new Task('1695','329');


if( assert($test->getNextStatus()) ) {
    echo 1;
} else {
    echo 0;
}
if( assert($test->getActionForStatus()) ) {
    echo 1;
} else {
    echo 0;
}
if( assert(Task::getAllStatusesActions()) ) {
    echo 1;
} else {
    echo 0;
}



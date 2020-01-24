<?php
use src\classes;

require_once 'vendor/autoload.php';

$task = new classes\Task(2,3, 'начать');   echo '<br>';
$task = new classes\Task(2,3, 'отказаться');      echo '<br>';
$task = new classes\Task(2,3, 'в работе');      echo '<br>';
$task = new classes\Task(2,3, 'завершить');      echo '<br>';
$task = new classes\Task(2,3, 'отменить');      echo '<br>';

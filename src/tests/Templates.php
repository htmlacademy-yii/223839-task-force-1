<?php


namespace src\tests;

use Logic\Task;
use src\Logic\actions\{ActionStart, ActionRefusal, ActionComplete, ActionCancel};

class Templates
{

    public static function getTask($customerID, $performerID)
    {
        return new Task($customerID,$performerID);
    }

}

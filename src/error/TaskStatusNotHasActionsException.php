<?php


namespace src\error;


class TaskStatusNotHasActionsException extends BaseException
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}

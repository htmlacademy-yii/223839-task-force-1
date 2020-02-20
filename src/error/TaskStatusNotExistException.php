<?php


namespace src\error;


class TaskStatusNotExistException extends BaseException
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}

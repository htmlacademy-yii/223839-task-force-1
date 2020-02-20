<?php


namespace src\error;


class ActionNotExistException extends BaseException
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}

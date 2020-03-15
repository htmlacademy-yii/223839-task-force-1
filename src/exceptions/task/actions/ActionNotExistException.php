<?php


namespace src\exceptions;


class ActionNotExistException extends BaseException
{
    public function __construct($message = null)
    {
        $this->message = $message ?? 'TaskAction not exist';
        parent::__construct($this->message);
    }
}

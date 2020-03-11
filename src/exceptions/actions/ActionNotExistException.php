<?php


namespace src\error;


class ActionNotExistException extends BaseException
{
    public function __construct($message = null)
    {
        $this->message = $message ?? 'Action not exist';
        parent::__construct($this->message);
    }
}

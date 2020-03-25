<?php


namespace Exceptions;


class TaskStatusNotExistException extends BaseException
{
    public function __construct($message = null)
    {
        $this->message = $message ?? 'Status not exist';
        parent::__construct($this->message);
    }
}

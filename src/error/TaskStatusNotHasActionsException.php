<?php


namespace src\error;


class TaskStatusNotHasActionsException extends BaseException
{
    public function __construct($message = null)
    {
        $this->message = $message ?? 'Status don\'t have actions';
        parent::__construct($this->message);
    }
}

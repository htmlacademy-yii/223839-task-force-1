<?php


namespace Src\Exceptions;


class TaskStatusNotHasActionsException extends BaseException
{
    public function __construct($message = null)
    {
        $this->message = $message ?? 'Status don\'t have Actions';
        parent::__construct($this->message);
    }
}

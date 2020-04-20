<?php

namespace Exceptions\Task\actions;

class ActionNotExistException extends BaseException
{
    public function __construct($message = null)
    {
        $this->message = $message ?? 'TaskAction not exist';
        parent::__construct($this->message);
    }
}

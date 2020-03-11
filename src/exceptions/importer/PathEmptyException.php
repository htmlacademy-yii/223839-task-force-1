<?php


namespace src\exceptions;


class PathEmptyException extends BaseException
{
    public function __construct($message = null)
    {
        $this->message = $message ?? 'Path empty';
        parent::__construct($this->message);
    }
}

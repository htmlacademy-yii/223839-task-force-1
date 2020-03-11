<?php


namespace src\exceptions;


class IsNotFileException extends BaseException
{
    public function __construct($message = null)
    {
        $this->message = $message ?? 'This is not a file';
        parent::__construct($this->message);
    }
}

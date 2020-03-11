<?php


namespace src\exceptions;


class FileDoesNotExistException extends BaseException
{
    public function __construct($message = null)
    {
        $this->message = $message ?? 'file does not exist';
        parent::__construct($this->message);
    }
}

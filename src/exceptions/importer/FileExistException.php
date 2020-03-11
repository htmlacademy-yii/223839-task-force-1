<?php


namespace src\exceptions;


class FileExistException extends BaseException
{
    public function __construct($message = null)
    {
        $this->message = $message ?? 'file already exists';
        parent::__construct($this->message);
    }
}

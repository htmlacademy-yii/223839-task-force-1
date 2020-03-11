<?php


namespace src\exceptions;


class IsNotWritableException extends BaseException
{
    public function __construct($message = null)
    {
        $this->message = $message ?? 'This file is not writable';
        parent::__construct($this->message);
    }
}

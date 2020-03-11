<?php


namespace src\exceptions;


class ContentIsNullException extends BaseException
{
    public function __construct($message = null)
    {
        $this->message = $message ?? 'Content is null';
        parent::__construct($this->message);
    }
}

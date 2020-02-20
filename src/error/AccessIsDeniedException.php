<?php


namespace src\error;


class AccessIsDeniedException extends BaseException
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}

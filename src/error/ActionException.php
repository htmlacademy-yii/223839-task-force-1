<?php


namespace src\error;


class ActionException extends BaseException
{
    public function __construct($message, $file, $line, $action)
    {
        echo $file . " " . $line . ' ' . $action;
        parent::__construct($message);
    }
}

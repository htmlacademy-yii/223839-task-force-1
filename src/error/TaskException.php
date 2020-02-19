<?php


namespace src\error;


class TaskException extends BaseException
{
    public function __construct($message, $file, $line, $status)
    {
        echo $file . " " . $line . ' ' . $status . ' ';
        parent::__construct($message);
    }
}

<?php

namespace src\error;

use Exception;
use Throwable;

class ErrorHandler extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}

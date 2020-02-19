<?php

namespace src\error;

use Exception;

class ErrorHandler extends Exception
{
    public function __construct($message, $file)
    {
        echo $file . '<br>';
        parent::__construct($message);
    }
}

<?php


namespace src\exceptions\fileSystem;

use src\exceptions\BaseException;

class ThisIsNotFile extends BaseException
{
    public function __construct($message = null)
    {
        $this->message = $message ?? 'This is not a file';
        parent::__construct($this->message);
    }
}

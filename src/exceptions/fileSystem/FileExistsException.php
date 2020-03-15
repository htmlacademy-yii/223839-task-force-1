<?php


namespace src\exceptions\fileSystem;

use src\exceptions\BaseException;

class FileExistsException extends BaseException
{
    public function __construct($message = null)
    {
        $this->message = $message ?? 'File already exists';
        parent::__construct($this->message);
    }
}

<?php

namespace Exceptions\FileSystem;

use Exceptions\BaseException;

class KeyNotExistException extends BaseException
{
    public function __construct($message = null)
    {
        $this->message = $message ?? 'key not exist in data';
        parent::__construct($this->message);
    }
}

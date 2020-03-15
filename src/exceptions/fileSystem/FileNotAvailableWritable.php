<?php

namespace src\exceptions\fileSystem;

use src\exceptions\BaseException;

class FileNotAvailableWritable extends BaseException
{
    public function __construct($message = null)
    {
        $this->message = $message ?? 'the file is not available for writable';
        parent::__construct($this->message);
    }
}

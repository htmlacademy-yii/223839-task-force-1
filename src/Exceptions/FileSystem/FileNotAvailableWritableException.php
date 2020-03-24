<?php

namespace Src\Exceptions\FileSystem;

use Src\Exceptions\BaseException;

class FileNotAvailableWritableException extends BaseException
{
    public function __construct($message = null)
    {
        $this->message = $message ?? 'the file is not available for writable';
        parent::__construct($this->message);
    }
}

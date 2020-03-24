<?php


namespace Src\Exceptions\FileSystem;

use Src\Exceptions\BaseException;

class FileDoesNotExistsException extends BaseException
{
    public function __construct($message = null)
    {
        $this->message = $message ?? 'File does not exists';
        parent::__construct($this->message);
    }
}

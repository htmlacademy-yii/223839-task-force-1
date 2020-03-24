<?php


namespace Src\Exceptions\FileSystem;

use Src\Exceptions\BaseException;

class FileExistsException extends BaseException
{
    public function __construct($message = null)
    {
        $this->message = $message ?? 'File already exists';
        parent::__construct($this->message);
    }
}

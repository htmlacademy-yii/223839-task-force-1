<?php


namespace Src\Exceptions;


class AccessIsDeniedException extends BaseException
{
    public function __construct($message = null)
    {
        $this->message = $message ?? 'Access is denied';
        parent::__construct($this->message);
    }
}

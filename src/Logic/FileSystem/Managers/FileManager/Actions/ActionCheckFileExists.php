<?php

namespace Logic\FileSystem\Managers\FileManager\Actions;

use Exceptions\FileSystem\ThisIsNotFileException;

class ActionCheckFileExists implements ActionFile
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function checkFileExists(): bool
    {
        if ( ! file_exists($this->path)) {
            return false;
        }
        if ( ! is_file($this->path)) {
            throw new ThisIsNotFileException();
        }

        return true;
    }
}

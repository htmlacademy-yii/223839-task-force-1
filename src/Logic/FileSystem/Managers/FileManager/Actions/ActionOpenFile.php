<?php

namespace Logic\FileSystem\Managers\FileManager\Actions;

use Exceptions\FileSystem\FileDoesNotExistsException;

class ActionOpenFile implements ActionFile
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function openFile(string $mode = 'r')
    {
        if (file_exists($this->path)) {
            throw new FileDoesNotExistsException();
        }

        return fopen($this->path, $mode);
    }
}

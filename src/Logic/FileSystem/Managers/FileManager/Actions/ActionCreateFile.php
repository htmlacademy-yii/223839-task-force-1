<?php

namespace Logic\FileSystem\Managers\FileManager\Actions;

class ActionCreateFile implements ActionFile
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function createFile(): void
    {
        if (file_exists($this->path)) {
            throw new FileExistsException();
        }
        $fhandler = fopen($this->path, 'w');
        $this->closeFile($fhandler);
    }
}

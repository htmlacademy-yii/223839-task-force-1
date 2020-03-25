<?php

namespace Logic\FileSystem\Managers\FileManager\Actions;

use Exceptions\FileSystem\ThisIsNotFileException;

class ActionSaveFile implements ActionFile
{
    private string $data;

    public function __construct(string $data)
    {
        $this->data = $data;
    }

    public function saveFile($handler): void
    {
        if ( ! is_resource($handler)) {
            throw new ThisIsNotFileException();
        }

        fwrite($handler, $this->data);
    }
}

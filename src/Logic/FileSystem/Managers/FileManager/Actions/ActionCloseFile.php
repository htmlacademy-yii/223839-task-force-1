<?php

namespace Logic\FileSystem\Managers\FileManager\Actions;

use Exceptions\FileSystem\ThisIsNotFileException;

class ActionCloseFile implements ActionFile
{
    public function closeFile($fileHandler): void
    {
        if ( ! is_resource($fileHandler)) {
            throw new ThisIsNotFileException();
        }
        fclose($fileHandler);
    }
}

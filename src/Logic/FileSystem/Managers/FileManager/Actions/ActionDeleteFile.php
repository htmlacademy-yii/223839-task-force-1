<?php


namespace Logic\FileSystem\Managers\FileManager\Actions;


use Exceptions\FileSystem\FileDoesNotExistsException;
use Exceptions\FileSystem\ThisIsNotFileException;

class ActionDeleteFile implements ActionFile
{
    private string $pathDelete;

    public function __construct(string $pathDelete)
    {
        $this->pathDelete = $pathDelete;
    }

    public function deleteFile(): void
    {
        if ( ! file_exists($this->pathDelete)) {
            throw new FileDoesNotExistsException();
        }
        if ( ! is_file($this->pathDelete)) {
            throw new ThisIsNotFileException();
        }

        unlink($this->pathDelete);
    }
}

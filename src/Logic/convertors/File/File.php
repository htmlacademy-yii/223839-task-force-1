<?php


namespace Logic\convertors\File;


use Logic\convertors\File\Interfaces\FileInterface;
use src\exceptions\FileDoesNotExistException;
use src\exceptions\FileExistException;

class File implements FileInterface
{
    private string $filePath;
    public Reader $reader;
    public Content $content;
    public Writer $writer;

    public function __construct(string $path)
    {
        $this->filePath = $path;
        $this->reader = new Reader($this->getFile());
        $this->content = new Content($this->getFile());
        $this->writer = new Writer($this->getFile());
    }

    public function createFile(string $path)
    {
        if (file_exists($path)) {
            throw new FileExistException();
        }
        if (!file_exists($path)) {
            $saveFile = fopen($path, 'w');
            fwrite($saveFile, '');
            fclose($saveFile);
        }
    }

    public function getFile(): \SplFileObject
    {
        if (!file_exists($this->filePath)) {
            throw new FileDoesNotExistException();
        }
        return new \SplFileObject($this->filePath);
    }

    public function getFileInfo(): \SplFileInfo
    {
        return new \SplFileInfo($this->filePath);
    }

    public function saveFile(): void
    {
        // TODO: Implement saveFile() method.
    }
}

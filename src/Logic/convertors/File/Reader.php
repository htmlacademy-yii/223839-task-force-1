<?php


namespace Logic\convertors\File;


use Logic\convertors\File\Interfaces\ReaderInterface;

class Reader implements ReaderInterface
{
    public function __construct(\SplFileObject $file)
    {
        $this->file = $file;
    }

    public function readFile(): void
    {
        while ($this->file->valid()) {
            echo $this->file->fgets();
        }
    }
}

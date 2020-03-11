<?php


namespace Logic\convertors\File;


use Logic\convertors\File\Interfaces\WriterInterface;

class Writer implements WriterInterface
{
    public function __construct(\SplFileObject $file)
    {
        $this->file = $file;
    }

    public function write(): void
    {

    }
}

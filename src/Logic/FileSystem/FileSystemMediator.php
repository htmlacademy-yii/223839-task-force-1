<?php


namespace Logic\FileSystem;


use Logic\convertors\File\Writer;
use Logic\FileSystem\FileActions\FileActionOpen;
use Logic\FileSystem\FileActions\FileActionRead;

class FileSystemMediator
{
    public Reader $reader;
    public Writer $writer;

    public function __construct()
    {
        $this->reader = $this->createReader();
        $this->writer = $this->createWriter();
    }

    private function createReader()
    {
        return new Reader();
    }

    private function createWriter()
    {
        return new Writer();
    }
}

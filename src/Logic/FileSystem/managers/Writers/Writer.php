<?php

namespace Logic\FileSystem\managers\Writers;

use Logic\FileSystem\managers\IWriter;
use src\exceptions\fileSystem\FileExistsException;
use src\exceptions\fileSystem\ThisIsNotFile;

class Writer implements IWriter
{
    public function createFile(string $path)
    {
        if(file_exists($path)){
            throw new FileExistsException();
        }
        $fhandler = fopen($path, 'w');
        $this->closeFile($fhandler);
    }

    public function writeInFile($file, string $content): void
    {
        if(!is_resource($file)) {
            throw new ThisIsNotFile();
        }
        fwrite($file, $content);
    }

    public function closeFile($fileHandler): void
    {
        if(!is_resource($fileHandler)) {
            throw new ThisIsNotFile();
        }
        fclose($fileHandler);
    }
}

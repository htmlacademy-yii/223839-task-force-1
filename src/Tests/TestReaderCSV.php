<?php

namespace Src\Tests;

use Logic\FileSystem\Managers\Readers\ReaderCSV;

class TestReaderCSV
{
    protected string $dir = 'test.csv';

    public function testOpenFile() : bool
    {
        $reader = new ReaderCSV();
        $filePath = $this->dir;
        return assert($reader->openFile($filePath), 'Could not open file');
    }
}

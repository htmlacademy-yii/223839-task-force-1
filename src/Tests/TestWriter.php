<?php


namespace Src\Tests;


use Logic\FileSystem\Managers\Writers\Writer;

class TestWriter
{
    public function testCreateFile(): bool
    {
        $writer   = new Writer();
        $filePath = __DIR__.'/test.test';
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $writer->createFile($filePath);
        if (file_exists($filePath)) {
            return true;
        }
    }

    public function testWriteInFile(): void
    {
        $writer   = new Writer();
        $filePath = 'test.csv';
        $file     = fopen($filePath, 'w');
        $writer->writeInFile($file, '');
    }

    public function testCloseFile()
    {
        $writer = new Writer();
        $filePath = 'test.csv';
        $file     = fopen($filePath, 'r');
        $writer->closeFile($file);
    }
}

<?php


namespace Tests;


use Logic\FileSystem\Managers\Writers\WriterSQL;

class TestWriter
{
    public function testCreateFile(): bool
    {
        $writer   = new WriterSQL();
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
        $writer   = new WriterSQL();
        $filePath = 'test.csv';
        $file     = fopen($filePath, 'w');
        $writer->writeInFile($file, '');
    }

    public function testCloseFile()
    {
        $writer = new WriterSQL();
        $filePath = 'test.csv';
        $file     = fopen($filePath, 'r');
        $writer->closeFile($file);
    }
}

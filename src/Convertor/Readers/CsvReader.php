<?php

namespace Convertor\Readers;

use Convertor\Base\ReaderInterface;

class CsvReader implements ReaderInterface
{
    private string $pathFile;

    public function setFile($filename)
    {
        $this->pathFile = $filename;
    }

    public function readItem(): \Generator
    {
        // тут происходит считывание одной строки данных
        $file = new \SplFileObject($this->pathFile);

        $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY);
        while ($file->valid()) {
            yield $file->fgetcsv();
        }
    }
}


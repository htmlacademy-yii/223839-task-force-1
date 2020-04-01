<?php

namespace Convertor\Readers;

use Convertor\Base\DtoItem;
use Convertor\Base\ReaderInterface;

class CsvReader implements ReaderInterface
{
    private string $pathFile;

    public function setFile($filename): void
    {
        $this->pathFile = $filename;
    }

    public function readItem(): DtoItem
    {
        $file = new \SplFileObject($this->pathFile);
        $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY);

        $generator = function ($file) {
            while ($file->valid()) {
                yield $file->fgetcsv();
            }
        };

        $data = [];
        foreach ($generator($file) as $content) {
            $data[] = $content;
        }

        $tableName   = array_shift($data);
        $columnsName = array_shift($data);

        return new DtoItem($tableName, $columnsName, $data);
    }
}


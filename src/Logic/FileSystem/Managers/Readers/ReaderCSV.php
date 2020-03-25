<?php

namespace Logic\FileSystem\Managers\Readers;

use Logic\FileSystem\Data\DTO;
use Logic\FileSystem\Data\IDTO;
use Logic\FileSystem\Managers\IReader;

class ReaderCSV implements IReader
{
    public function readData(string $path): array
    {
        $data = [];
        foreach ($this->getReaderGenerator($path) as $values) {
            $data[] = $values;
        }

        return $data;
    }

    private function getReaderGenerator(string $path): \Generator
    {
        $handler = $this->initSplFileObject($path);
        while ($handler->valid()) {
            yield $handler->fgetcsv();
        }
    }

    private function initSplFileObject(string $path): \SplFileObject
    {
        $handler = new \SplFileObject($path);
        $handler->setFlags(\SplFileObject::SKIP_EMPTY);

        return $handler;
    }
}

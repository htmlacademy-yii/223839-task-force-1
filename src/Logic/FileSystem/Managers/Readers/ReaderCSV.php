<?php

namespace Logic\FileSystem\Managers\Readers;

use Logic\FileSystem\Data\DTO;
use Logic\FileSystem\Data\IDTO;
use Logic\FileSystem\Managers\IReader;

class ReaderCSV implements IReader
{
    public function readData(string $path): IDTO
    {
        $DTO = new DTO();
        foreach ($this->generateData($path) as $value) {
            $data[] = $value;
        }
        $DTO->setData($data);
        return $DTO;
    }

    private function generateData(string $path): \Generator
    {
        $handler = $this->initSplFileObject($path);
        while ($handler->valid()) {
            yield $handler->fgetcsv();
        }
    }

    private function initSplFileObject(string $path): \SplFileObject
    {
        $handler = new \SplFileObject($path);
        $handler->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY);

        return $handler;
    }
}

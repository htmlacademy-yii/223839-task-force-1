<?php

namespace Logic\FileSystem\Converter;

use Logic\FileSystem\Managers\IReader;
use Logic\FileSystem\Managers\IWriter;

class Converter
{
    private IReader $reader;
    private IWriter $writer;

    public function __construct(
        IReader $reader,
        IWriter $writer
    ) {
        $this->reader = $reader;
        $this->writer = $writer;
    }

    public function startConvert(string $handlerPath): void
    {
        $DTO = $this->reader->readData($handlerPath);
        $this->writer->writeData($DTO->getData());
    }

    public function resetData(): void
    {
        $this->writer->resetData();
    }

    public function getData(): string
    {
        return $this->writer->getData();
    }
}

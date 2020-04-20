<?php

namespace Convertor;

use Convertor\Base\ReaderInterface;
use Convertor\Base\WriterInterface;

class Convertor
{
    private ReaderInterface $reader;
    private WriterInterface $writer;

    public function __construct(ReaderInterface $reader, WriterInterface $writer)
    {
        $this->reader = $reader;
        $this->writer = $writer;
    }

    public function convert(): void
    {
        foreach ($this->reader->readItem() as $data) {
                $this->writer->writeData($data);
        }
        $this->writer->saveData();
    }
}


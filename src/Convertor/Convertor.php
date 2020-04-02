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
        echo '<pre>';
        $i = 0;
        foreach ($this->reader->readItem() as $data) {
            $i++;
            $this->writer->writeItem($data);
            if($this->reader->endFile) {
                $this->writer->saveFile();
                echo 'finish ' . $i;
            };
        }
    }
}


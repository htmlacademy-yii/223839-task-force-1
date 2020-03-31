<?php

namespace Convertor;

use Convertor\Base\ReaderInterface;
use Convertor\Base\WriterInterface;

class Convertor
{
    private $reader;
    private $writer;

    public function __construct(ReaderInterface $reader, WriterInterface $writer)
    {
        $this->reader = $reader;
        $this->writer = $writer;
    }

    public function convert()
    {
        $i = 0;
        //было
        // $data = $this->reader->readItem();
        while($i <= 0) {
            $data = $this->reader->readItem();
            $this->writer->writeItem($data);
            $i++;
        }
    }
}

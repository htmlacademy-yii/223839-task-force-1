<?php

namespace Convertor\Writers;

use Convertor\Base\DtoItem;
use Convertor\Base\WriterInterface;

class SqlWriter implements WriterInterface
{
    private string $path;

    public function setPath($path): void
    {
        $this->path = $path;
    }

    public function writeItem(DtoItem $data): void
    {
        $insert = implode(',', $data->getTitle());
        $insert .= implode(',', $data->getColumns());
        $insert .= implode(',', $data->getData());

        $file = fopen($this->path.implode('', $data->getTitle()).'.sql', 'a');
        fwrite($file, $insert);
        fclose($file);
    }
}


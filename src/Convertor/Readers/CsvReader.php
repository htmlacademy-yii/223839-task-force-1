<?php

namespace Convertor\Readers;

use Convertor\Base\DtoItem;
use Convertor\Base\ReaderInterface;

class CsvReader implements ReaderInterface
{
    private string $pathFile;
    public bool $endFile = false;

    public function setFile($filename): void
    {
        $this->pathFile = $filename;
    }

    public function readItem()
    {
        $file = new \SplFileObject($this->pathFile);
        $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::DROP_NEW_LINE);

        $fileName      = $file->getFilename();
        $fileExtension = '.'.$file->getExtension();

        // ubrat MASSIV
        $fileName = [str_replace($fileExtension, '', $fileName)];


        $i = 0;
        $checkEndFile = function ($file) {
            if($file->eof()) {
                $this->endFile = true;
            }
        };
        while ( ! $file->eof()) {


            if ($i === 0) {
                $columns = $file->current();
            }

            $file->next();
            $checkEndFile($file);
            yield new DtoItem($fileName, $columns, $file->current());
            $i++;
        }
    }
}


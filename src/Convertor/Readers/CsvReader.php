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

    public function readItem()
    {
        $file = new \SplFileObject($this->pathFile);
        $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY);

        $fileName      = $file->getFilename();
        $fileExtension = '.' . $file->getExtension();

        $fileName = str_replace($fileExtension, '', $fileName);


        $i = 0;
        while ( ! $file->eof()) {
            if ($i === 0) {
                $columns = $file->current();
            }

            $file->next();
            if($file->current()){
                yield new DtoItem($fileName, $columns, $file->current());
            }
            $i++;
        }
    }
}


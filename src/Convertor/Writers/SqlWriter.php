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
        $insert = "INSERT INTO `%1s`(`%2s`)\n";
        $insert = sprintf($insert, implode($data->getTitle()), implode('`,`', $data->getColumns()));
        $insert .= "VALUES ";
        foreach ($data->getData() as $values) {
            if ( ! empty($values)) {
                $insert .= "(";

                foreach ($values as $value) {
                    if (is_numeric($value)) {
                        $insert .= "{$value},";
                    } else {
                        $insert .= "'{$value}',";
                    }
                }

                // удаление последней запятой в строке
                $insert = substr($insert, 0, -1);
                $insert .= "),\n";
            }
        }
        // удаление последней запятой и переноса строки в тексте
        $insert = substr($insert, 0, -2);
        $insert .= ';';


        $file = fopen($this->path.implode('', $data->getTitle()).'.sql', 'w');
        fwrite($file, $insert);
        fclose($file);
    }
}

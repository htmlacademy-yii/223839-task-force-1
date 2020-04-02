<?php

namespace Convertor\Writers;

use Convertor\Base\DtoItem;
use Convertor\Base\WriterInterface;

class SqlWriter implements WriterInterface
{
    private string $path;
    private string $content = '';
    private string $insertInto;

    private $title;
    private $columns;

    public function setPath($path): void
    {
        $this->path = $path;
    }

    public function writeItem(DtoItem $data, $endFile = false): void
    {
        $this->title   = implode('', $data->getTitle());
        $this->columns = implode('`,`', $data->getColumns());

        $formatToValueType = function ($values) {
            $formatValues = [];
            foreach ($values as $value) {
                // если тип не numeric, ставит ковычки
                $value          = is_numeric($value) ? $value : "'$value'";
                $formatValues[] = $value;
            }

            return $formatValues;
        };

        $values = implode(',', $formatToValueType($data->getData()));

        $insertInto = "INSERT INTO `{$this->title}` (`{$this->columns}`) \n VALUES ";

        $insert = '';
        $insert .= "({$values}), \n";



        $this->insertInto = $insertInto;

        // записывает полученную строку в переменную
        $this->content    .= $insert;
    }

    public function saveFile()
    {
        $insert = $this->insertInto.$this->content;

        $file = fopen($this->path.$this->title.'.sql', 'w');
        fwrite($file, $insert);
        fclose($file);

        $this->insertInto = '';
    }
}


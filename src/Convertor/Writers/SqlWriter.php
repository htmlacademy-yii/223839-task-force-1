<?php

namespace Convertor\Writers;

use Convertor\Base\DtoItem;
use Convertor\Base\WriterInterface;

class SqlWriter implements WriterInterface
{
    private string $path;
    private string $insertInto;
    private string $insertValues = '';

    private DtoItem $data;

    public function setPath($path): void
    {
        $this->path = $path;
    }

    public function writeData(DtoItem $data): void
    {
        $this->data = $data;

        if (!is_null($data)) {
            $insertIntoStr = "INSERT INTO `%1s` (`%2s`) \n VALUES ";
            $this->insertInto = sprintf($insertIntoStr, $this->getTitle(), $this->getColumns());

            $values = implode(',', $this->formatValues($data->getData()));
            $this->insertValues .= "({$values}), \n";
        }
    }

    public function saveData(): void
    {
        $insert = $this->insertInto . $this->insertValues;
        $insert = substr($insert, 0, -3) . ';';

        $file = fopen($this->path . $this->getTitle() . '.sql', 'w');
        fwrite($file, $insert);
        fclose($file);
    }

    private function getTitle(): string
    {
        return $this->data->getTitle();
    }

    private function getColumns(): string
    {
        return implode('`,`', $this->data->getColumns());
    }

    private function formatValues($values): array
    {
        $formatValues = [];
        foreach ($values as $value) {
            $formatValues[] = is_numeric($value) ? $value : "'$value'";
        }

        return $formatValues;
    }
}


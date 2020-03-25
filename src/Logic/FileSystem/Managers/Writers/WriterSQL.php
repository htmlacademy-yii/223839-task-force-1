<?php

namespace Logic\FileSystem\Managers\Writers;

use Logic\FileSystem\Data\IDTO;
use Logic\FileSystem\Managers\IWriter;

class WriterSQL implements IWriter
{
    private IDTO $DTO;
    private array $content = [];

    public function getWriteData(IDTO $DTO): string
    {
        $this->DTO     = $DTO;
        $this->content = $this->DTO->__get('content');

        return $this->getFormationQuery();
    }

    private function getFormationQuery(): string
    {
        $insert = "INSERT INTO %1s(%2s)\n";
        $insert = sprintf($insert, $this->getTableNameForSQL(), $this->getColumnNamesForSQL());
        $insert .= "VALUES ";
        $insert .= $this->getValuesQuery()."\n";

        return $insert;
    }

    private function getValuesQuery(): string
    {
        return $this->formationValuesQuery();
    }

    private function formationValuesQuery(): string
    {
        $insert = '';
        foreach ($this->content as $values) {
            if ( ! empty($values)) {
                $insert .= "(";
                foreach ($values as $value) {
                    $insert .= $this->formationValue($value);
                }
                // удаление последней запятой в строке
                $insert = substr($insert, 0, -1);
                $insert .= "),\n";
            }
        }
        // удаление последней запятой и переноса строки в тексте
        $insert = substr($insert, 0, -2);
        $insert .= ';';

        return $insert;
    }


    private function formationValue(?string $value): string
    {
        if (is_numeric($value)) {
            return "{$value},";
        } else {
            return "'{$value}',";
        }
    }

    private function getTableNameForSQL(): string
    {
        return '`'.$this->DTO->__get('fileName')[0].'`';
    }

    private function getColumnNamesForSQL(): string
    {
        $namesGroup = array_shift($this->content);
        $counter    = count($namesGroup);

        return $this->formationColumnsNames($counter, $namesGroup);
    }

    private function formationColumnsNames(int $counter, array $namesGroup): string
    {
        for ($index = 0; $index < $counter; $index++) {
            if ($index === $counter - 1) {
                $names[] = "`{$namesGroup[$index]}`";

                return implode(',', $names);
            }
            $names[] = "`{$namesGroup[$index]}`";
        }
    }
}

<?php

namespace Logic\FileSystem\Managers\Writers;

use Logic\FileSystem\Data\CSV\CSVDTO;
use Logic\FileSystem\Managers\IWriter;
use Src\Exceptions\FileSystem\FileExistsException;
use Src\Exceptions\FileSystem\ThisIsNotDTOException;
use Src\Exceptions\FileSystem\ThisIsNotFileException;

class WriterSQL implements IWriter
{
    private CSVDTO $CSVDTO;

    public function createFile(string $path)
    {
        if (file_exists($path)) {
            throw new FileExistsException();
        }
        $fhandler = fopen($path, 'w');
        $this->closeFile($fhandler);
    }

    public function writeInFile($fileHandler, string $content): void
    {
        if ( ! is_resource($fileHandler)) {
            throw new ThisIsNotFileException();
        }

        fwrite($fileHandler, $content);
    }

    public function closeFile($fileHandler): void
    {
        if ( ! is_resource($fileHandler)) {
            throw new ThisIsNotFileException();
        }
        fclose($fileHandler);
    }

    public function getContent($dto): string
    {
        if ( ! ($dto instanceof CSVDTO)) {
            throw new ThisIsNotDTOException('this is not CSVDTO');
        }
        $this->CSVDTO = $dto;
        return $this->formationQuery();
    }

    private function formationQuery(): string
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
        foreach ($this->CSVDTO->getContent() as $values) {
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
        return '`'.$this->CSVDTO->getFileName().'`';
    }

    private function getColumnNamesForSQL(): string
    {
        $namesGroup = $this->CSVDTO->getContent()[0];
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

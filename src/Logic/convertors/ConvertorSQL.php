<?php

namespace Logic\Convertors;

use Logic\convertors\ConvertorTemplate\ConvertorInterface;
use \Logic\convertors\File\File;

class ConvertorSQL implements ConvertorInterface
{
    private File $convertFile;
    private $content;
    private string $pathToSave;

    public function __construct(string $pathToFile, string $pathToSave)
    {
        $this->convertFile = new File($pathToFile);
        $this->pathToSave = $pathToSave;
        $this->content = $this->convertFile->content->generateContent();
    }

    private function getTableName()
    {
        $ext = $this->convertFile->getFileInfo()->getExtension();
        $pattern = '/.' . $ext . '/xi';
        $tableName = '`' . preg_replace($pattern, '', $this->convertFile->getFileInfo()->getFilename()) . '`';
        return $tableName;
    }

    private function getColumnsNames()
    {
        $columnsNames = array_shift($this->content);
        $lenghtArr = count($columnsNames);
        for ($i = 0; $i < $lenghtArr; $i++) {
            if ($i === $lenghtArr - 1) {
                $array[] = "{$columnsNames[$i]}";
                return '`' . implode('`,`', $columnsNames) . '`';
            }
            $array[] = "`{$columnsNames[$i]}`,";
        }
    }

    public function getInsertQuery()
    {
        array_pop($this->content);
        $insert = "INSERT INTO  %1s(%2s)\n";
        $insert = sprintf($insert, $this->getTableName(), $this->getColumnsNames());
        $insert .= 'VALUES ';
        foreach ($this->content as $values) {
            if (!empty($values)) {
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
        return $insert;
    }

    public function convert(): void
    {
        $this->getInsertQuery();

    }
}

<?php

namespace Logic\FileSystem\Convertors;

use Logic\FileSystem\data\sql\InsertQuery;
use Logic\FileSystem\managers\Readers\ReaderCSV;
use Logic\FileSystem\managers\Writers\Writer;
use Logic\FileSystem\IConvertor;

class ConvertorCSVtoSQL implements IConvertor
{
    private ReaderCSV $reader;
    private Writer $writer;
    private \SplFileObject $file;
    private array $content;

    public function __construct(ReaderCSV $reader, Writer $writer)
    {
        $this->reader = $reader;
        $this->writer = $writer;
    }

    public function convertData($pathToFile): string
    {
        $this->file = new \SplFileObject($pathToFile);
        $this->file->setFlags(\SplFileObject::SKIP_EMPTY);

        $this->content = $this->reader->readFile($this->file);
        $tableName     = $this->getTableName();
        $columnsNames  = $this->getColumnsNames();

        $insertQuery = new InsertQuery($tableName, $columnsNames, $this->content);

        return $insertQuery->getQuery();
    }

    private function getTableName(): string
    {
        $fileName        = $this->file->getFilename();
        $fileDescription = $this->file->getExtension();

        return str_replace(".{$fileDescription}", '', $fileName);
    }

    private function getColumnsNames(): array
    {
        return $this->content[0];
    }

}

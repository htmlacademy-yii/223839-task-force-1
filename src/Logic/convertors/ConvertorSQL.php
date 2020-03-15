<?php

namespace Logic\convertors;

use Logic\convertors\ConvertorInterface;
use Logic\FileSystem\FileSystemMediator;
use Logic\data\sql\SQLdata;

class ConvertorSQL implements ConvertorInterface
{
    public function __construct(string $pathFiles)
    {
        $this->fileSystemMediator = new FileSystemMediator();
        $this->files = $this->fileSystemMediator->reader->getSPLFilesObjectsGroup($pathFiles);
    }

    public function convert(): string
    {
        $query = '';
        foreach ($this->files as $file) {
            $tableName = $this->getTableName($file);
            $content = $this->fileSystemMediator->reader->getFileContent($file);
            $columnsNames = $this->getColumnsNames($content);
            $sql = new SQLdata($tableName, $columnsNames, $content);
            $query .= $sql->getQuery();
        }
        return $query;
    }

    private function getTableName($file)
    {
        return $this->fileSystemMediator->reader->getFileNameWithoutExtension($file);
    }

    private function getColumnsNames(array $content)
    {
        return implode(',', $content[0]);
    }
}

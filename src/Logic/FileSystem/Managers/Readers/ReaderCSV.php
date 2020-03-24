<?php

namespace Logic\FileSystem\Managers\Readers;

use Logic\FileSystem\Data\CSV\CSVDTO;
use Logic\FileSystem\Managers\IReader;
use Src\Exceptions\FileSystem\FileDoesNotExistsException;
use Src\Exceptions\FileSystem\ThisIsNotFileException;

class ReaderCSV implements IReader
{
    public function openFile(string $path, string $mode = 'r')
    {
        if ( ! file_exists($path)) {
            throw new FileDoesNotExistsException();
        }

        return fopen($path, $mode);
    }

    public function checkExistFile(string $filePath): bool
    {
        if ( ! file_exists($filePath)) {
            return false;
        }
        if ( ! is_file($filePath)) {
            throw new ThisIsNotFileException();
        }

        return true;
    }

    public function readFile(\SplFileObject $file): array
    {
        $content = [];
        foreach ($this->getReaderGenerator($file) as $values) {
            $content[] = $values;
        }

        return $content;
    }

    public function getDTO(\SplFileObject $file): CSVDTO
    {
        return $this->initCSVDTO($file);
    }

    private function initCSVDTO(\SplFileObject $file) : CSVDTO
    {
        $content = $this->readFile($file);
        $fileName = $this->getFileName($file, true);
        return new CSVDTO($content, $fileName);
    }

    private function getReaderGenerator(\SplFileObject $file): \Generator
    {
        while ($file->valid()) {
            yield $file->fgetcsv();
        }
    }

    private function getFileName(\SplFileObject $file, bool $cutFileExtension = false)
    {
        $fileName = $file->getFilename();
        if ($cutFileExtension) {
            $fileDescription = $file->getExtension();

            return str_replace(".{$fileDescription}", '', $fileName);
        }

        return $fileName;
    }
}

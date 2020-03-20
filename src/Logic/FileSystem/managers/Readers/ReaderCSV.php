<?php

namespace Logic\FileSystem\managers\Readers;

use Logic\FileSystem\managers\IReader;
use src\exceptions\fileSystem\FileDoesNotExistsException;
use src\exceptions\fileSystem\ThisIsNotFile;

class ReaderCSV implements IReader
{
    public function openFile(string $path, string $mode = 'r')
    {
        if ( ! file_exists($path)) {
            throw new FileDoesNotExistsException();
        }

        return fopen($path, $mode);
    }

    public function checkExistFile($filePath): bool
    {
        if (!file_exists($filePath)) {
            return false;
        }
        if ( ! is_file($filePath)) {
            throw new ThisIsNotFile();
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

    private function getReaderGenerator(\SplFileObject $file): \Generator
    {
        while ($file->valid()) {
            yield $file->fgetcsv();
        }
    }
}

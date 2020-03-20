<?php

require_once '../vendor/autoload.php';

class ConvertRunner
{
    public function __construct()
    {
        $this->reader    = new \Logic\FileSystem\managers\Readers\ReaderCSV();
        $this->writer    = new \Logic\FileSystem\managers\Writers\Writer();
        $this->convertor = new \Logic\FileSystem\Convertors\ConvertorCSVtoSQL($this->reader, $this->writer);
    }

    public function startConvert(string $dirPath, string $fileSavePath, array $queueGroup)
    {
        $files   = $this->getSortQueueFiles($queueGroup, $dirPath);
        $content = $this->getContentToWrite($files);

        if ($this->reader->checkExistFile($fileSavePath)) {
            unlink($fileSavePath);
        }

        $this->writer->createFile($fileSavePath);
        $file = $this->reader->openFile($fileSavePath, 'w');

        $this->writer->writeInFile($file, $content);
        $this->writer->closeFile($file);
    }

    private function getSortQueueFiles(array $queueGroup, string $dirPath): array
    {
        $files = [];
        for ($index = 0; $index < count($queueGroup); $index++) {
            $files[] = $dirPath.DIRECTORY_SEPARATOR."{$queueGroup[$index]}";
        }

        return $files;
    }

    private function getContentToWrite(array $files): string
    {
        $content = '';
        foreach ($files as $filePath) {
            $content .= $this->convertor->convertData($filePath);
        }

        return $content;
    }
}

$dir          = '../data/';
$pathFileSave = __DIR__.'/database/queris/query.sql';
$queue        = [
    'cities.csv',
    'categories.csv',
    'users.csv',
    'tasks.csv',
    'reviews.csv',
    'responses.csv',
];

$convertor = new ConvertRunner();
$convertor->startConvert($dir, $pathFileSave, $queue);

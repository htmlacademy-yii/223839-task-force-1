<?php

use Logic\FileSystem\Data\IDTO;
use Logic\FileSystem\Managers\IReader;
use Logic\FileSystem\Managers\IWriter;

require_once '../vendor/autoload.php';

class ConvertRunner
{
    private IReader $reader;
    private IWriter $writer;
    private IDTO $DTO;

    private string $content = '';
    private array $files;

    public function __construct(IReader $reader, IWriter $writer)
    {
        $this->reader = $reader;
        $this->writer = $writer;
    }

    public function startConvert(string $dirPath, string $fileSavePath, array $queueGroup): void
    {
        $this->files = $this->getSortQueueFiles($queueGroup, $dirPath);
        foreach ($this->files as $file) {
            $file = new SplFileObject($file);
            $file->setFlags(SplFileObject::SKIP_EMPTY);

            $this->DTO     = $this->reader->getDTO($file);
            $this->content .= $this->writer->getContent($this->DTO);

        }
        $this->saveResult($fileSavePath);
    }

    private function saveResult(string $fileSavePath): void
    {
        if ($this->reader->checkExistFile($fileSavePath)) {
            unlink($fileSavePath);
        }

        $this->writer->createFile($fileSavePath);
        $file = $this->reader->openFile($fileSavePath, 'w');

        $this->writer->writeInFile($file, $this->content);
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

}

$dir          = '../data/';
$fileSavePath = __DIR__.'/database/queris/query.sql';
$queue        = [
    'cities.csv',
    'categories.csv',
    'users.csv',
    'tasks.csv',
    'reviews.csv',
    'responses.csv',
];
$reader       = new \Logic\FileSystem\Managers\Readers\ReaderCSV();
$writer       = new \Logic\FileSystem\Managers\Writers\WriterSQL();

$convertRunner = new ConvertRunner($reader, $writer);
$convertRunner->startConvert($dir, $fileSavePath, $queue);

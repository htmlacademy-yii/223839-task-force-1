<?php

use Logic\FileSystem\Converter;
use Logic\FileSystem\Data\IDTO;
use Logic\FileSystem\Managers\IReader;
use Logic\FileSystem\Managers\IWriter;
use Logic\FileSystem\Managers\IContentManager;

require_once '../vendor/autoload.php';

class ConvertRunner
{
    private IDTO $DTO;
    private IReader $reader;
    private IWriter $writer;
    private Converter $converter;
    private IContentManager $contentManager;
    private string $data;

    public function __construct(
        IDTO $dto,
        IReader $reader,
        IWriter $writer,
        Converter $converter,
        IContentManager $contentManager
    ) {
        $this->DTO            = $dto;
        $this->reader         = $reader;
        $this->writer         = $writer;
        $this->converter      = $converter;
        $this->contentManager = $contentManager;
    }

    public function startConvert(): void
    {
        $this->data = $this->converter->converting($this->DTO, $this->writer);
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function saveData(string $pathSave, string $data): void
    {
        $this->contentManager->saveContent($pathSave, $data);
    }
}


////////////////////////////////////////////////////////////////
////////////////////     data       ////////////////////////////
////////////////////////////////////////////////////////////////

$dir          = '../data/';
$fileSavePath = __DIR__.'/database/queries/query.sql';
$queue        = [
    'cities.csv',
    'categories.csv',
    'users.csv',
    'tasks.csv',
    'reviews.csv',
    'responses.csv',
];

$files = [];
for ($index = 0; $index < count($queue); $index++) {
    $files[] = $dir.DIRECTORY_SEPARATOR."{$queue[$index]}";
}

$DTO            = new \Logic\FileSystem\Data\DTO();
$reader         = new \Logic\FileSystem\Managers\Readers\ReaderCSV();
$writer         = new \Logic\FileSystem\Managers\Writers\WriterSQL();
$fileManager    = new \Logic\FileSystem\Managers\FileManager\FileManager();
$converter      = new \Logic\FileSystem\Converter();
$contentManager = new \Logic\FileSystem\Managers\SaveContentManagers\ContentFilesManager($fileManager);

////////////////////////////////////////////////////////////////
////////////////////     logic       ///////////////////////////
////////////////////////////////////////////////////////////////

$data = '';
foreach ($files as $filePath) {
    $fileName = $fileManager->getHandlerName($filePath);
    $content  = $reader->readData($filePath);

    $DTO->__set('fileName', $fileName);
    $DTO->__set('content', $content);

    $converterRunner = new ConvertRunner($DTO, $reader, $writer, $converter, $contentManager);
    $converterRunner->startConvert($filePath);
    $data .= $converterRunner->getData();
}

$converterRunner->saveData($fileSavePath, $data);

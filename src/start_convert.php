<?php

use Logic\FileSystem\Converter\Converter;
use Logic\FileSystem\Converter\ConverterDTO;

require_once '../vendor/autoload.php';

$dir      = '..'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR;
$savePath = __DIR__.'/database/queries/query.sql';
$queue    = [
    'cities.csv',
    'categories.csv',
    'users.csv',
    'tasks.csv',
    'reviews.csv',
    'responses.csv',
];

$reader         = new \Logic\FileSystem\Managers\Readers\ReaderCSV();
$writer         = new \Logic\FileSystem\Managers\Writers\WriterSQL();
$fileManager    = new \Logic\FileSystem\Managers\FileManager\FileManager();
$contentManager = new \Logic\FileSystem\Managers\SaveContentManagers\ContentFilesManager($fileManager);

$converterDTO = new ConverterDTO($reader, $writer, $contentManager);

$converterDTO->queue    = $queue;
$converterDTO->savePath = $savePath;
$converterDTO->prefix   = $dir;

function getHandlersQueue($DTO): array
{
    $handlers = [];
    $queue    = $DTO->queue;
    $prefix   = $DTO->prefix;
    for ($index = 0; $index < count($queue); $index++) {
        $handlers[] = $prefix."{$queue[$index]}";
    }

    return $handlers;
}

$converter = new Converter($converterDTO->reader, $converterDTO->writer);

$data = '';
foreach (getHandlersQueue($converterDTO) as $handler) {
    $converter->startConvert($handler);

    $data .= $converter->getData();

    $converter->resetData();
}
$converterDTO->contentManager->saveContent($converterDTO->savePath, $data);

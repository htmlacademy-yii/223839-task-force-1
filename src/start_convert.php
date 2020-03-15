<?php

require_once '../vendor/autoload.php';

use Logic\convertors\ConvertorSQL;

class ConvertRunner
{
    public function convert(string $pathFilesDir, string $pathToSaveFile)
    {
        $convertor = new ConvertorSQL($pathFilesDir);
        $query = $convertor->convert();
    }
}

$dir = '../data/';

$filePath = $dir . DIRECTORY_SEPARATOR . 'cities.csv';
$pathFileToSave = __DIR__ . '/database/queris/quersy.sql';

$convertor = new ConvertRunner();
$convertor->convert($dir, $filePath);


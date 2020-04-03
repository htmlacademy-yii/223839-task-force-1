<?php

require_once '../vendor/autoload.php';

use Convertor\Convertor;
use Convertor\Readers\CsvReader;
use Convertor\Writers\SqlWriter;

$csvBasePath = '../data/csv/';
$sqlBasePath = '../data/sql/';
$files = ['cities.csv', 'categories.csv', 'users.csv', 'tasks.csv', 'reviews.csv' ,'responses.csv', ];

foreach ($files as $file)
{
    $csvReader = new CsvReader();
    $csvReader->setFile($csvBasePath . $file);

    $sqlWriter = new SqlWriter();
    $sqlWriter->setPath($sqlBasePath);

    $convertor = new Convertor($csvReader, $sqlWriter);

    $convertor->convert();
}

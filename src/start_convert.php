<?php
require_once '../vendor/autoload.php';

use Logic\Convertors\ConvertorSQL;

$dir = '../data/';

$filePath = $dir . DIRECTORY_SEPARATOR . 'cities.csv';
$dirToSave = __DIR__ . '/database/queris/quersy.sql';

$convertor = new ConvertorSQL($filePath, $dirToSave);
try {
    $convertor->convert();
} catch (\src\exceptions\BaseException $e) {
    echo $e->getMessage();
}




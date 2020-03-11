<?php
require_once '../vendor/autoload.php';

use Logic\Convertors\ConvertorSQL;


$dir = '../data/';
function debug($ar)
{
    echo '<pre>';
    echo print_r($ar, true);
    echo '</pre>';
}

$filePath = $dir . DIRECTORY_SEPARATOR . 'cities.csv';
$dirToSave = __DIR__ . '/database/queris/quersy.sql';


//$filePath = $dir . DIRECTORY_SEPARATOR . 'test.ini';


$test = new ConvertorSQL($filePath, $dirToSave);
try {
    $test->convert();
} catch (\src\exceptions\BaseException $e) {
    echo $e->getMessage();
}

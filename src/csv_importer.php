<?php
require_once '../vendor/autoload.php';

use Logic\ImporterCSV;

$dir = '../data/';
$dirToSave = __DIR__ . '/database/queris/';


$import = new ImporterCSV();

$import->run($dir, $dirToSave);


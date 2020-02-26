<?php
require_once '../vendor/autoload.php';

use Logic\ImporterCSV;

$dir = '../data/';
$dirSQL_files =__DIR__ . '/database/queris/';

$files = scandir($dir);

foreach ($files as $file) {
    $pathOfFile = "$dir/$file";
    if (is_file($pathOfFile)) {
        $import = new ImporterCSV();
z        $import->run($pathOfFile, $dirSQL_files);
    }
}


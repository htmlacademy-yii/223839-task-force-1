<?php

namespace Logic\FileSystem\managers;

interface IWriter
{
    public function createFile(string $path);

    public function writeInFile($file, string $content): void;

    public function closeFile($file): void;
}

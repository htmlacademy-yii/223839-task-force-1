<?php

namespace Logic\FileSystem\Managers;

interface IWriter
{
    public function createFile(string $path);

    public function writeInFile($file, string $content): void;

    public function closeFile($file): void;

    public function getContent($dto): string;
}

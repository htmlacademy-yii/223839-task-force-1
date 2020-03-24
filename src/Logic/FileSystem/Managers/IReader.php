<?php

namespace Logic\FileSystem\Managers;

interface IReader
{
    public function openFile(string $path, string $mode);

    public function checkExistFile(string $filePath) : bool;

    public function readFile(\SplFileObject $file): array;

    public function getDTO(\SplFileObject $file);

}

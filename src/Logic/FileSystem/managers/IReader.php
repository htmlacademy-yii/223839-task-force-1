<?php

namespace Logic\FileSystem\managers;

interface IReader
{
    public function openFile(string $path, string $mode);

    public function readFile(\SplFileObject $file): array;
}

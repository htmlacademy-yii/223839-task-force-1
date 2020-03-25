<?php

namespace Logic\FileSystem\Managers;

use Logic\FileSystem\Data\DTO;
use Logic\FileSystem\Data\IDTO;

interface IReader
{
    public function readData(string $path): array;
}

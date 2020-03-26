<?php

namespace Logic\FileSystem\Managers;

use Logic\FileSystem\Data\IDTO;

interface IReader extends IManager
{
    public function readData(string $path): IDTO;
}

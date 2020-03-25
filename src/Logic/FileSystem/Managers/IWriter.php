<?php

namespace Logic\FileSystem\Managers;

use Logic\FileSystem\Data\IDTO;

interface IWriter
{
    public function getWriteData(IDTO $DTO): string;
}

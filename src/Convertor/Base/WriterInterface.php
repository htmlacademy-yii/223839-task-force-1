<?php

namespace Convertor\Base;

use Convertor\Base\DtoItem;

interface WriterInterface
{
    public function writeData(DtoItem $item): void;

    public function saveData(): void;
}

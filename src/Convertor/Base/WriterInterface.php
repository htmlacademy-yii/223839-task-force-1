<?php

namespace Convertor\Base;

use Convertor\Base\DtoItem;

interface WriterInterface
{
    public function writeItem(DtoItem $item): void;
}

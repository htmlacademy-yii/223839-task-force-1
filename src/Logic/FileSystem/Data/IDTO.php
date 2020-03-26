<?php

namespace Logic\FileSystem\Data;

interface IDTO
{
    public function __set($name, $value): void;

    public function __get($name);
}

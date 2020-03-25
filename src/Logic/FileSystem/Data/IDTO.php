<?php

namespace Logic\FileSystem\Data;

interface IDTO
{
    public function getData(): array;

    public function setData(array $data): void;

    public function __set($name, $value): void;

    public function __get($name);
}

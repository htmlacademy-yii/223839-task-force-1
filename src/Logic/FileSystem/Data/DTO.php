<?php

namespace Logic\FileSystem\Data;

use Exceptions\FileSystem\KeyNotExistException;

class DTO implements IDTO
{
    private array $data = [];

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function __get($name)
    {
        if ( ! array_key_exists($name, $this->data)) {
            throw new KeyNotExistException();
        }

        return $this->data[$name];
    }

    public function __set($name, $value): void
    {
        $this->data[$name] = $value;
    }
}

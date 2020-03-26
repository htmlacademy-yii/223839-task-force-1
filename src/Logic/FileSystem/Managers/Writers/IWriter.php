<?php

namespace Logic\FileSystem\Managers;

interface IWriter extends IManager
{
    public function writeData(array $data): void;

    public function resetData(): void;

    public function getData(): string;
}

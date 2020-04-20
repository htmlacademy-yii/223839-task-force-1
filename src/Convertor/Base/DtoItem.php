<?php

namespace Convertor\Base;

class DtoItem
{
    private string $title;
    private array $columns = [];
    private array $data = [];

    public function __construct(string $title, array $columns, array $data)
    {
        $this->title   = $title;
        $this->columns = $columns;
        $this->data    = $data;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getData(): array
    {
        return $this->data;
    }
}

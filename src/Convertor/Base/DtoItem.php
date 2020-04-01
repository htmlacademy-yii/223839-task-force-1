<?php

namespace Convertor\Base;

class DtoItem
{
    private array $title = [];
    private array $columns = [];
    private array $data = [];

    public function __construct($title, $columns, $data)
    {
        $this->title = $title;
        $this->columns = $columns;
        $this->data = $data;
    }

    public function getTitle(): array
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

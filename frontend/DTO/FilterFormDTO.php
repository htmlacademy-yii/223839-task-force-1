<?php

namespace frontend\DTO;

use yii\db\ActiveQuery;

class FilterFormDTO
{
    private ActiveQuery $query;
    private array       $data;
    private int         $pageSize;

    public function __construct(ActiveQuery $query, array $data, int $pageSize = 5)
    {
        $this->query = $query;
        $this->data = $data;
        $this->pageSize = $pageSize;
    }

    /**
     * @return ActiveQuery
     */
    public function getQuery(): ActiveQuery
    {
        return $this->query;
    }

    /**
     * @param ActiveQuery $query
     */
    public function setQuery(ActiveQuery $query): void
    {
        $this->query = $query;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * @param int $pageSize
     */
    public function setPageSize(int $pageSize): void
    {
        $this->pageSize = $pageSize;
    }
}
<?php


namespace Logic\data\sql;

class SQLdata
{
    public string $tableName;
    public string $columnsNames;
    public array $content;

    public function __construct(string $tableName, string $columnsNames, array $content)
    {
        $this->tableName = $tableName;
        $this->columnsNames = $columnsNames;
        $this->content = $content;
    }

    public function getQuery(): string
    {
        $query = new SQLQuery(
            new SQLdata(
                $this->tableName,
                $this->columnsNames,
                $this->content
            ));
        return $query->getQuery();
    }
}


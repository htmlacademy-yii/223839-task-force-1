<?php

namespace Logic\FileSystem\data\sql;

class InsertQuery
{
    private string $tableName;
    private array $columnsNames;
    private array $content;

    public function __construct(string $tableName, array $columnsNames, array $content)
    {
        $this->tableName    = $tableName;
        $this->columnsNames = $columnsNames;
        $this->content      = $content;
        $this->unsetAlreadyUsesColumnsNames();
    }

    private function unsetAlreadyUsesColumnsNames(): void
    {
        if ($this->columnsNames === $this->content[0]) {
            unset($this->content[0]);
        }
    }

    public function getQuery(): string
    {
        return $this->formationQuery();
    }

    private function formationQuery(): string
    {
        $insert = "INSERT INTO %1s(%2s)\n";
        $insert = sprintf($insert, $this->getTableNameForSQL(), $this->getColumnNamesForSQL());
        $insert .= "VALUES ";
        $insert .= $this->getValuesQuery()."\n";

        return $insert;
    }

    private function getValuesQuery(): string
    {
        return $this->formationValuesQuery();
    }

    private function formationValuesQuery(): string
    {
        $insert = '';
        foreach ($this->content as $values) {
            if ( ! empty($values)) {
                $insert .= "(";
                foreach ($values as $value) {
                    $insert .= $this->formationValue($value);
                }
                $insert = $this->cutSymbols($insert, 0, -1);
                $insert .= "),\n";
            }
        }
        $insert = $this->cutSymbols($insert, 0, -2);
        $insert .= ';';

        return $insert;
    }

    private function cutSymbols(
        string $str,
        int $begin,
        int $end
    ): string {

        $str = substr($str, $begin, $end);

        return $str;
    }

    private function formationValue(?string $value): string
    {
        if (is_numeric($value)) {
            return "{$value},";
        } else {
            return "'{$value}',";
        }
    }

    private function getTableNameForSQL(): string
    {
        return '`'.$this->tableName.'`';
    }

    private function getColumnNamesForSQL(): string
    {
        $namesGroup = $this->columnsNames;
        $counter    = count($namesGroup);

        return $this->formationColumnsNames($counter, $namesGroup);
    }

    private function formationColumnsNames(int $counter, array $namesGroup): string
    {
        for ($index = 0; $index < $counter; $index++) {
            if ($index === $counter - 1) {
                $names[] = "`{$namesGroup[$index]}`";

                return implode(',', $names);
            }
            $names[] = "`{$namesGroup[$index]}`";
        }
    }

}

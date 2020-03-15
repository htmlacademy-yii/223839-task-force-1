<?php

namespace Logic\data\sql;


class SQLQuery
{
    private SQLdata $data;

    public function __construct(SQLdata $dataForQuery)
    {
        $this->data = $dataForQuery;
        $this->unsetAlreadyUsesColumnsNames();
        $this->getQuery();
    }

    public function getQuery()
    {
        return $this->formationQuery();
    }

    private function formationQuery()
    {
        $insert = "INSERT INTO %1s(%2s)\n";
        $insert = sprintf($insert, $this->getTableNameForSQL(), $this->getColumnNamesForSQL());
        $insert .= "VALUES ";
        $insert .= $this->getValuesInsertQuery() . "\n";
        return $insert;
    }

    private function unsetAlreadyUsesColumnsNames(): void
    {

        /// исправить
        array_pop($this->data->content);



        if ($this->data->columnsNames === implode(',', $this->data->content[0])) {
            unset($this->data->content[0]);
        }
    }

    private function getTableNameForSQL()
    {
        return '`' . $this->data->tableName . '`';
    }

    private function getColumnNamesForSQL()
    {
        $namesGroup = explode(',', $this->data->columnsNames);
        $counter = count($namesGroup);
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

    private function getValuesInsertQuery()
    {
        return $this->formationInsertQuery();
    }

    private function formationInsertQuery()
    {
        $insert = '';
        foreach ($this->data->content as $values) {
            if (!empty($values)) {
                $insert .= "(";
                foreach ($values as $value) {
                    $insert .= $this->formationValue($value);
                }
// удаление последней запятой в строке
                $insert = substr($insert, 0, -1);
                $insert .= "),\n";
            }
        }
        $insert = substr($insert, 0, -2);
        $insert .= ';';
// удаление последнего переноса строки
        return $insert;
    }

    private function formationValue($value)
    {
        if (is_numeric($value)) {
            return "{$value},";
        } else {
            return "'{$value}',";
        }
    }
}

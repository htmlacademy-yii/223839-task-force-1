<?php

namespace Logic;

/**
 * Класс импортирует данные из CSV в запрос в SQL файле
 *
 * Class ImporterCSV
 * @package Logic
 */
class ImporterCSV
{
    /**
     * @var array массив с данными из файла
     */
    private array $csv_values = [];

    /**
     * @var string содержит строку с именами столбцов
     */
    private string $columnNames;

    /**
     * @var string имя таблицы
     */
    private string $tableName;

    /**
     * @var string sql запрос к базе данных в виде строки
     */
    private string $request = '';

    public function run(string $dir, string $dirToSave): void
    {
        foreach (scandir($dir) as $file) {
            $pathOfFile = "$dir/$file";
            if (is_file($pathOfFile)) {
                $this->setValues($pathOfFile);

                $this->setColumnNames();

                $this->setRequest();

                $this->request .= $this->setRequest();
            }
        }
        $this->createFile($dirToSave, $this->request);

    }

    /**
     * Инициализирует объект класса SplFileObject и записывает имя таблицы
     * @param string $file
     * @return \SplFileObject
     */
    private function initSplFile(string $file): \SplFileObject
    {
        $this->tableName = pathinfo($file)['filename'];

        $this->$file = $file;

        $file = new \SplFileObject($file);

        return $file;
    }

    /**
     * Метод парсит экземпляр объекта и генерирует массив со значениями
     * @param \SplFileObject $file
     * @return \Generator
     */
    private function generateValues(\SplFileObject $file): \Generator
    {
        $file->setFlags(\SplFileObject::READ_CSV);
        while ($file->valid()) {
            yield $file->fgetcsv();
        }
    }

    /**
     * Метод записывает сгенерированные значения в массив $csv_values
     */
    private function setValues($file): void
    {
        $generator = $this->generateValues($this->initSplFile($file));
        foreach ($generator as $value) {
            if (!in_array(null, $value, true)) {
                array_push($this->csv_values, $value);
            }
        }
    }

    /**
     * Метод записывает имена столбцов в строку
     */
    private function setColumnNames(): void
    {
        // запись имен столбцов
        $this->columnNames = implode('`,`', $this->csv_values[1]);
        $this->columnNames = trim($this->columnNames);


        // удаление имен столбца из массива со значениями
        array_shift($this->csv_values);

    }

    /**
     * Метод записывает SQL запрос к базе данных
     *
     * @uses $columnNames
     * @uses $csv_values
     * @uses $tableName
     */
    private function setRequest(): string
    {
        $request = "INSERT INTO `%1s`(`%2s`)\n";
        $request = sprintf($request, $this->tableName, $this->columnNames);
        $request .= "VALUES ";

        foreach ($this->csv_values as $values) {
            $request .= "(";
            foreach ($values as $value) {
                $value = trim($value);
                if (is_numeric($value)) {
                    $request .= "{$value},";
                } else {
                    $request .= "'{$value}',";
                }
            }
            // удаление запятой
            $request = substr($request, 0, -1);
            $request .= "),\n";
        }
        // удаление переноса и запятой у последнего элемента
        $request = substr($request, 0, -2) . ";\n";

        return $request;
    }

    /**
     * Метод записывает запрос в файл
     * @param string $dir
     * @param string $request
     */
    private function createFile(string $dir, string $request): void
    {
        $fp = fopen($dir . 'queries.sql', 'w');
        fwrite($fp, $request);
        fclose($fp);
    }
}

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
    private $csv_values = [];

    /**
     * @var array содержит массив с именами столбцов
     */
    private $columnNames;

    /**
     * @var string имя таблицы
     */
    private $tableName;


    public function run(string $filePath, string $dirSQL_files): void
    {
        $file = $this->initSplFile($filePath);

        $this->setValues($file);

        $this->setColumnNames();

        $request = $this->setRequest();

        $this->createFile($request, $dirSQL_files);
    }

    /**
     * Инициализирует объект класса SplFileObject и записывает имя таблицы
     * @param string $filePath путь к файлу
     * @return \SplFileObject экземпляр объекта класса SplFileObject
     */
    private function initSplFile(string $filePath): \SplFileObject
    {
        $file = new \SplFileObject($filePath);

        $this->tableName = pathinfo($filePath)['filename'];

        return $file;
    }

    /**
     * Метод парсит экземпляр объекта и генерирует массив со значениями
     * @param \SplFileObject $file
     * @return \Generator
     */
    private function generateValues(\SplFileObject $file): \Generator
    {
        while (!$file->eof()) {
            yield $file->fgetcsv();
        }
    }

    /**
     * Метод обходит null значения и записывает сгенерированные значения в массив $csv_values
     * @param \SplFileObject $file
     */
    private function setValues(\SplFileObject $file): void
    {
        foreach ($this->generateValues($file) as $value) {
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
        $this->columnNames = implode('`,`', $this->csv_values[0]);

        // удаление имен столбца из массива со значениями
        array_shift($this->csv_values);
    }

    /**
     * Метод записывает SQL запрос к базе данных
     *
     * @return string запрос к базе данных
     * @uses $columnNames
     * @uses $csv_values
     * @uses $tableName
     */
    private function setRequest(): string
    {
        $columnNames = trim($this->columnNames);
        $request = "INSERT INTO `$this->tableName`(`{$columnNames}`)\n";
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
        $request = substr($request, 0, -2);

        return $request;
    }

    /**
     * Метод записывает запрос в файл
     * @param string $request строка запроса для записи
     * @param string $dir путь куда записать файлы
     */
    private function createFile(string $request, string $dir): void
    {
        $fp = fopen($dir . $this->tableName . '-query.sql', 'w');
        fwrite($fp, $request);
        fclose($fp);
    }
}

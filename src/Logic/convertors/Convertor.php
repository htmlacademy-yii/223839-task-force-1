<?php


namespace Logic\Convertors;

use src\exceptions\BaseException;
use src\exceptions\ContentIsNullException;
use src\exceptions\FileDoesNotExistException;
use src\exceptions\IsNotFileException;
use src\exceptions\PathEmptyException;


class OBJETMY
{
    private $id;
    private $count;
    private $price;

    public function __construct($id, $count, $price)
    {
        $this->id = $id;
        $this->count = $count;
        $this->price = $price;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getCost()
    {
        return $this->getCount() * $this->getPrice();
    }
}


interface File
{
    public function getFile(): \SplFileObject;

    public function generateContent(): \Generator;

    public function createFile(string $content): void;

    public function getFileInfo(): \SplFileInfo;
}

interface FileReaderInterface
{
    public function readFile(bool $print = false): void;
}

interface ConventorInterface extends FileReaderInterface
{
    public function convert(): void;
}

/**
 * Class FileToConverting
 * @package Logic\Importer
 */
class FileToConverting implements File
{
    private string $filePath;
    private \SplFileObject $file;

    public function __construct(string $path, bool $createFile = false, string $content = null)
    {
        $this->filePath = $path;
        if ($createFile === false) {
            try {
                $this->file = $this->getFile();
            } catch (BaseException $e) {
                echo $e->getMessage();
                die;
            }
        } else {
            try {
                $this->createFile($content);
            } catch (BaseException $e) {
                echo $e->getMessage();
                die;
            }
        }
    }

    public function getFile(): \SplFileObject
    {
        if (!file_exists($this->filePath)) {
            throw new FileDoesNotExistException();
        }
        if (!is_file($this->filePath)) {
            throw new IsNotFileException();
        }
        return new \SplFileObject($this->filePath);
    }

    public function createFile(string $content): void
    {
        if (empty($this->filePath)) {
            throw new PathEmptyException();
        }
        if ($content === null) {
            throw new ContentIsNullException();
        }
        $saveFile = fopen($this->filePath, 'w');
        fwrite($saveFile, $content);
        fclose($saveFile);
    }

    public function generateContent(): \Generator
    {
        while ($this->file->valid()) {
            yield $this->file->fgets();
        }
    }

    public function getFileInfo(): \SplFileInfo
    {
        return $this->file->getFileInfo();
    }

}

class Convertor implements ConventorInterface
{
    protected FileToConverting $file;
    protected string $pathToSaveFile;
    protected string $content = '';

    public function __construct(string $path, string $pathToSaveFile)
    {
        $this->file = new FileToConverting($path);
        $this->pathToSaveFile = $pathToSaveFile;
    }

    public function readFile(bool $print = false): void
    {
        foreach ($this->file->generateContent() as $string) {
            if ($print === false) {
                $this->content .= $string;
            } else {
                echo $string;
            }
        }
    }

    public function convert(): void
    {
        $this->readFile();

        new FileToConverting($this->pathToSaveFile, true, $this->content);
    }
}


namespace Logic\Convertors;


/**
 * Класс импортирует данные из CSV в запрос в SQL файле
 *
 * Class ImporterCSV
 * @package Logic
 */
class ConvertorCSVtoSQL extends Convertor
{
    protected FileToConverting $file;
    protected string $pathToSaveFile;
    protected string $content = '';

    public function __construct(string $path, string $pathToSaveFile)
    {
        parent::__construct($path, $pathToSaveFile);
    }

    public function convert(): void
    {
        $sql = new SQLquery($this->file);
        $this->content = $sql->getRequest();
        parent::convert();
    }
}

class SQLquery
{

    /**
     * @var array массив с данными из файла
     */
    private array $csv_values = [];
    private FileToConverting $file;

    public function __construct(FileToConverting $file)
    {
        $this->file = $file;
        $this->setValues();
    }

    private function getTableName()
    {
        return preg_replace('/\.[a-zA-Z0-9а-яА-я]*/i', '', $this->file->getFileInfo()->getFilename());
    }

    /**
     * Метод записывает сгенерированные значения в массив $csv_values
     */
    private function setValues(): void
    {
        $this->csv_values = [];
        foreach ($this->file->generateContent() as $value) {
            if ($value) {
                array_push($this->csv_values, $value);
            }
        }
    }

    private function getColumnNames()
    {
        $columnNames = explode(',', array_shift($this->csv_values));
        $columnNames = '`' . trim(implode('`,`', $columnNames));
        $columnNames .= '`';
        return $columnNames;
    }

    private function generateInsertValues($arr)
    {
//        foreach ($arr as $item) {
//            if (is_numeric($item)) {
//                echo gettype($item) . ' INT ';
//            } else {
//                echo gettype($item) .' STRING ';
//            }
//        }
//        debug($arr);

    }

    /**
     * Method returns an array with values ​​for insert request
     * @return array
     */
    private function getValuesQuery(): array
    {
        $this->getColumnNames();
        $insert = [];
        $insertEnd = trim(array_pop($this->csv_values));
        $insertEnd = "({$insertEnd})";
        foreach ($this->csv_values as $values) {
            $this->csv_values;
            $values = explode(',', $values);
            debug($values);
            echo gettype($values[2]);
            $this->generateInsertValues($values);

            $values = implode(',', $values);
            array_push($insert, "({$values}),");
        }
        array_push($insert, $insertEnd);
        return $insert;
    }


    /**
     * Метод записывает SQL запрос к базе данных
     *
     * @uses $csv_values
     * @uses $tableName
     */
    public function getRequest(): string
    {
        $request = "INSERT INTO `%1s`(%2s)\n";
        $request = sprintf($request, $this->getTableName(), $this->getColumnNames());
        $request .= "VALUES ";
        foreach ($this->getValuesQuery() as $value) {
            $request .= "{$value}\n";
        }
        return $request;
    }
}



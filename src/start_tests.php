<?php
require_once '../vendor/autoload.php';

use Logic\Task;
use src\Logic\actions\{ActionStart, ActionRefusal, ActionComplete, ActionCancel};

class StartTest
{
    public static $pathArray = [];
    public static $nameClasses = [];

    public function __construct()
    {
        $this->searchTestMethods();
    }

    /*
     * получает путь к классам
     */
    public static function getPath()
    {
        // сканирует папку с тестами
        $dir = scandir('tests\\');
        // префикс для пути к файлу
        $dirStr = 'tests/';
        for($i = 0; $i < count($dir); $i++ ) {
            // если значение true
            if( strstr($dir[$i], 'Test' ) ) {
                // записываем путь к тестам
                $path = $dirStr . strstr($dir[$i], 'Test');
                // убирает лишнии классы
                if(!strstr($dir[$i], 'Test.')) {
                    array_push(self::$pathArray, $path);
                }
            }
        }
    }

    /**
     * подключает классы с префиксом test
     */
    public static function includeClass()
    {
        foreach (self::$pathArray as $item) {
            $nameClass = substr($item, 0, -4);
            $nameClass = str_replace('tests/', '', $nameClass);
            $nameClass = ('src\tests\\' . $nameClass);
            array_push(self::$nameClasses, $nameClass);
            include_once $item;
        }
    }

    /*
     *
     */
    public static function registerload()
    {
        self::getPath();
        self::includeClass();
    }

    /*
     * ищет методы для теста
     */
    public function searchTestMethods()
    {
        self::registerload();
        foreach (StartTest::$nameClasses as $name) {
            $reflection = new ReflectionClass($name);
            foreach ($reflection->getMethods() as $method) {
                if (strstr($method->name, 'test')) {
                    $method->invoke($reflection->newInstance());
                }
            }
        }
    }
}
$startTest = new StartTest();

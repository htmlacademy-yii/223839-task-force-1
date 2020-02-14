<?php

require_once '../vendor/autoload.php';

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

    public function searchTestMethods() : void
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

    public static function registerload() : void
    {
        self::getPath();
        self::includeClass();
    }

    public static function getPath() : void
    {
        // сканирует папку с тестами
        $dir = scandir('tests\\');
        // префикс для пути к файлу
        $dirStr = 'tests/';
        for ($i = 0; $i < count($dir); $i++) {
            // если значение true
            if (strstr($dir[$i], 'Test')) {
                // записываем путь к тестам
                $path = $dirStr . strstr($dir[$i], 'Test');
                // убирает лишнии классы
                if ( ! strstr($dir[$i], 'Test.')) {
                    array_push(self::$pathArray, $path);
                }
            }
        }
    }

    /*
     * ищет методы для теста
     */

    /**
     * подключает классы с префиксом test
     */
    public static function includeClass() : void
    {
        foreach (self::$pathArray as $item) {
            $nameClass = substr($item, 0, -4);
            $nameClass = str_replace('tests/', '', $nameClass);
            $nameClass = ('src\tests\\' . $nameClass);
            array_push(self::$nameClasses, $nameClass);
            include_once $item;
        }
    }
} $startTest = new StartTest();


try {
    $task = new \Logic\Task(1,2);
    $task->getActionForStatus(1);
    $task->getActionForStatus(3);
    $task->getNextStatus(new \src\Logic\actions\ActionCancel());
    $task->getNextStatus(new \src\Logic\actions\ActionStart());
    $task->getNextStatus(new \src\Logic\actions\ActionRefusal());
    $task->getNextStatus(new \src\Logic\actions\ActionComplete());
    $action = new \src\Logic\actions\ActionStart();
    $action->checkRights(1,2,2);
    $action = new \src\Logic\actions\ActionCancel();
    $action->checkRights(1,2,1);
    $action = new \src\Logic\actions\ActionComplete();
    $action->checkRights(1,2,1);
    $action = new \src\Logic\actions\ActionRefusal();
    $action->checkRights(1,2,2);
} catch (\src\error\ErrorHandler $e) {
    exit($e->getMessage());
}

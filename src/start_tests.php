<style>
    body{
        margin: 3.25rem 3rem;
        background-color: rgba(53, 53, 53, 0.75);
        color: #000000;
    }
    pre {
        background: #BBBBBB;
        color: #333;
        font-size: 16px;
        border: #2f3542 1px solid;
        padding: 10px 10px;
        box-shadow: inset 0 0 0 1px rgba(0,0,0,.15);
        border-radius: 0 0 2px 2px;
    }
    div {
        background-color: #cac4b2;
        border: black solid 2px;
        margin-bottom: 20px;
        padding: 10px 30px;
    }
    </style>
<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root . '/vendor/autoload.php';

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
    ///////////////////////////////////////////////////////
    /*
     * получает путь к классам
     */
    public static function getPath()
    {
        // сканирует папку с тестами
        $dir = scandir('tests\\');
        // префикс для пути к файлу
        $dirStr = 'tests/';
        $counter = 0;
        for($i = 0; $i < count($dir); $i++ ) {
            // если значение true
            if( strstr($dir[$i], 'Test' ) ) {
                // записываем путь к тестам
                $path = $dirStr . strstr($dir[$i], 'Test');
                // убирает лишнии классы
                if(!strstr($dir[$i], 'Test.')) {
                    array_push(self::$pathArray, $path);
                    $counter++;
                }
            }
        }
    }
    ///////////////////////////////////////////////////////////////

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
        // удаление абстрактного класса
        unset(self::$nameClasses[array_search('src\tests\TestAbstract', self::$nameClasses)]);
    }

    /*
     * подключает классы
     */
    public function registerload()
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

        foreach (StartTest::$nameClasses as $title) {
            $reflection = new ReflectionClass($title);
            foreach ($reflection->getMethods() as $method) {
                if (strstr($method->name, 'test')) {
                    echo $method->invoke($reflection->newInstance());

                    echo '<br>';
//                    echo '<div>';
//                    echo ('Class: ' . $reflection->getShortName() .
//                          '<br>Method: ' . $method->name .
//                          '<br>File: ' . $reflection->getFileName() .
//                          '<br>Line: ' . $method->getStartLine() . '-' . $method->getEndLine() .
//                          '<br>Namespace: ' . $reflection->getNamespaceName() .'<br><hr>');
//                    echo $method->invoke($reflection->newInstance());
//                    echo '</div>';
                }
            }
        }
    }
}
$startTest = new StartTest();
//////////////////////////////////////////










// вывод подключенные классы
//debug(StartTest::$pathArray);
echo '<div><h1>Подключенные классы:</h1><ol>';

foreach (get_included_files() as $classes) {
    echo '<li>' . $classes . '</li>';
}
echo '</ol></div>';



$testTask = new Task(1, 2);
$testActionComplete = new ActionComplete();
$testActionCancel   = new ActionCancel();
$testActionRefusal  = new ActionRefusal();
$testActionStart    = new ActionStart();

//  равен ли статус после выполнения действия ожидаемому статусу
assert($testTask->getNextStatus($testActionStart::getInnerName()) === Task::STATUS_ACTIVE);
assert($testTask->getNextStatus($testActionRefusal::getInnerName()) === Task::STATUS_FAILED);
assert($testTask->getNextStatus($testActionCancel::getInnerName()) === Task::STATUS_CANCELED);
assert($testTask->getNextStatus($testActionComplete::getInnerName()) === Task::STATUS_COMPLETED);

// проверка идентичности объектов action в массиве разрешенных действий
assert($testTask->getActionForStatus(Task::STATUS_NEW) == [$testActionStart, $testActionCancel]);
assert($testTask->getActionForStatus(Task::STATUS_ACTIVE) == [$testActionComplete, $testActionRefusal]);

// присутствует ли Action в массиве разрешенных действий для данного статуса
assert(in_array($testActionCancel, $testTask->getActionForStatus(Task::STATUS_NEW)));
assert(in_array($testActionStart, $testTask->getActionForStatus(Task::STATUS_NEW)));
assert(in_array($testActionComplete, $testTask->getActionForStatus(Task::STATUS_ACTIVE)));
assert(in_array($testActionRefusal, $testTask->getActionForStatus(Task::STATUS_ACTIVE)));

// проверка прав пользователя для данного действия
assert($testActionStart->checkRights($testTask->customerID, $testTask->performerID, 2));
assert($testActionCancel->checkRights($testTask->customerID, $testTask->performerID, 1));
assert($testActionRefusal->checkRights($testTask->customerID, $testTask->performerID, 2));
assert($testActionComplete->checkRights($testTask->customerID, $testTask->performerID, 1));


function debug($arr) {
//    <pre><h1>DEBUG<h1><br></pre>
    echo '<pre>' . print_r($arr, true) . '</pre>';
}
function myEcho($str) {
    echo '<pre style="border: #00793c solid 1px;">' . $str . '</pre>';
}
function myDump($arr) {
    echo '<pre>';
    var_dump($arr);
    echo '</pre>';
}

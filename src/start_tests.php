<style>
    * {
        font-size: 0.98rem;
    }
    body{
        margin: 3.25rem 1rem;
        background-color: rgba(53, 53, 53, 0.75);
        color: #000000;
    }
    pre {
        background: #BBBBBB;
        color: #333;
        border: #2f3542 1px solid;
        padding: 10px 10px;
        box-shadow: inset 0 0 0 1px rgba(0,0,0,.15);
        border-radius: 0 0 2px 2px;
    }
    div {
        background-color: #cac4b2;
        border: black solid 2px;
        margin-bottom: 20px;
        padding: 10px 10px;
    }
    </style>
<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root . '/vendor/autoload.php';
require_once $root . '/src/tests/Templates.php';

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
        echo '<div><ol style="padding: 0px 30px;">';
        foreach (StartTest::$nameClasses as $name) {
            $reflection = new ReflectionClass($name);
            foreach ($reflection->getMethods() as $method) {
                if (strstr($method->name, 'test')) {
                    if($method->invoke($reflection->newInstance())) {
                        echo '<li style="margin: 10px 0;"><span style="font-weight: bold">Class:</span> ' . $reflection->getShortName() .
                             ' <span style="font-weight: bold">method: </span><span style="border-bottom: solid 0.5px; color: #262626; padding-bottom: 1px;">' . $method->name .
                             '</span> <span style="font-weight: bold">in:</span> ' . $reflection->getFileName() .
                             '</span> <span style="font-weight: bold">Line:</span> ' . $method->getStartLine() . '-' . $method->getEndLine() .
                             ' <span style="color: #2f802d; font-weight: bold;">is OKAY...</span></li>';
                    } else {
                        echo '<li><span style="color: #ba0000; font-weight: bold;">mistake in </span>' .
                             $method->getFileName() . ' <span style="color: #ba0000; font-weight: bold;">Line:</span> ' .
                             $method->getStartLine() . '-' . $method->getEndLine() . '</li>';
                    }
                }
            }
        }
        echo '</ol></div>';
    }
}

$startTest = new StartTest();

//////////////////////////////////////////
// вывод подключенных классов
echo '<div>';
echo '<pre><h2>Подключаемые тесты</h2><ol>';
foreach (StartTest::$pathArray as $plugin) {
    echo '<li>' . $plugin . '</li>';
};
echo '</ol></pre><h1>Подключенные классы:</h1><ol>';
foreach (get_included_files() as $classes) {
    echo '<li>' . $classes . '</li>';
}
echo '</ol></div>';



// функции удобночитаемого вывода
function debug($arr) {
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

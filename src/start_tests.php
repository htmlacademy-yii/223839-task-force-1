<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


require_once '../vendor/autoload.php';

class Tester
{
    private $pathArray = [];
    private $namespacesTests = [];

    /**
     * @throws ReflectionException
     */
    public function run(): void
    {
        $this->registerload();
        foreach ($this->namespacesTests as $namespace) {
            $reflection = new ReflectionClass($namespace);
            foreach ($reflection->getMethods() as $method) {
                if (strstr($method->name, 'test')) {
                    try {
                        $method->invoke($reflection->newInstance());
                    } catch (\src\error\BaseException $e) {
                        echo $e->getMessage();
                    }
                }
            }
        }
    }

    private function registerload(): void
    {
        $this->getPath();
        $this->includeTests();
        $this->generateNamespaces();
    }

    /**
     *  Получает путь к классам
     */
    private function getPath(): void
    {
        // сканирует папку с тестами
        $dir = scandir('tests/');
        // префикс для пути к файлу
        $dirStr = 'tests/';
        for ($i = 0; $i < count($dir); $i++) {
            // если значение true
            if (strstr($dir[$i], 'Test')) {
                // записываем путь к тестам
                $path = $dirStr . strstr($dir[$i], 'Test');
                // убирает лишнии классы
                if (!strstr($dir[$i], 'Test.')) {
                    array_push($this->pathArray, $path);
                }
            }
        }
    }

    /**
     * Подключает классы с префиксом test
     */
    private function includeTests(): void
    {
        foreach ($this->pathArray as $path) {
            include_once $path;
        }
    }

    /**
     * Генерирует namespace's для тестов
     */
    private function generateNamespaces(): void
    {
        foreach ($this->pathArray as $path) {
            // cut .php
            $nameTest = substr($path, 0, -4);
            // replace / on \ for correct namespace
            $nameTest = str_replace('/', '\\', $nameTest);
            // generate namespace
            $nameTest = ('src\\' . $nameTest);
            array_push($this->namespacesTests, $nameTest);
        }
    }
}

$tester = new Tester();
$tester->run();

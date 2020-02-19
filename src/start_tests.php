<?php

require_once '../vendor/autoload.php';

class Tester
{
    private $pathArray = [];
    private $namespacesTests = [];

    public function __construct()
    {
        $this->run();
    }

    private function run(): void
    {
        $this->registerload();
        foreach ($this->namespacesTests as $namespace) {
            $reflection = new ReflectionClass($namespace);
            foreach ($reflection->getMethods() as $method) {
                if (strstr($method->name, 'test')) {
                    try {
                        if ( ! $method->invoke($reflection->newInstance())) {
                            throw new \src\error\baseException('Ошибка вызова теста');
                        };
                    } catch (\src\error\ActionException $e) {
                        echo $e->getMessage() . PHP_EOL;
                    } catch (\src\error\TaskException $e) {
                        echo $e->getMessage() . PHP_EOL;
                    } catch (\src\error\BaseException $e) {
                        echo $e->getMessage() . PHP_EOL;
                    }
                }
            }
        }
    }

    private function registerload(): void
    {
        $this->getPath();
        $this->includeTests();
        $this->namespaceGenerator();
    }

    /**
     *  получает путь к классам
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
                if ( ! strstr($dir[$i], 'Test.')) {
                    array_push($this->pathArray, $path);
                }
            }
        }
    }

    /**
     * подключает классы с префиксом test
     */
    private function includeTests(): void
    {
        foreach ($this->pathArray as $path) {
            include_once $path;
        }
    }

    /**
     * генерирует namespace's для тестов
     */
    private function namespaceGenerator(): void
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

$startTest = new Tester();

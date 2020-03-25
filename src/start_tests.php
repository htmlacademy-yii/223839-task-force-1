<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '../vendor/autoload.php';

use Exceptions\BaseException;

class Tester
{
    private string $pathToTests = '.'.DIRECTORY_SEPARATOR.'Tests'.DIRECTORY_SEPARATOR;

    private array $pathArray = [];
    private array $pathsTests = [];

    public function runTest(): void
    {
        $this->registerload();
        foreach ($this->pathsTests as $pathToTest) {
            $pathToTest = 'tests\\'.$pathToTest;
            $test       = $pathToTest;
            $this->invokeTestMethod($test);
        }
    }

    private function invokeTestMethod($test)
    {

        $reflection = new ReflectionClass($test);
        foreach ($reflection->getMethods() as $method) {
            if (strstr($method->name, 'test')) {
                try {
                    $method->invoke($reflection->newInstance());
                } catch (BaseException $e) {
                    echo $e->getMessage();
                }
            }
        }
    }

    private function registerload(): void
    {
        $this->getPath();
        $this->getTestsNames();
        $this->includeTests();
    }

    private function getPath(): void
    {
        $tests = glob($this->pathToTests.DIRECTORY_SEPARATOR.'Test*.php');
        foreach ($tests as $test) {
            $path = $test;
            array_push($this->pathArray, $path);
        }
    }

    private function getTestsNames(): void
    {
        foreach ($this->pathArray as $path) {
            $file            = new SplFileObject($path);
            $fileDescription = $file->getExtension();
            $fileName        = $file->getFilename();
            $fileName        = str_replace(".{$fileDescription}", '', $fileName);
            $path            = $fileName;
            array_push($this->pathsTests, $path);
        }
    }

    private function includeTests(): void
    {
        foreach ($this->pathArray as $path) {
            require_once $path;
        }
    }
}

$tester = new Tester();
$tester->runTest();

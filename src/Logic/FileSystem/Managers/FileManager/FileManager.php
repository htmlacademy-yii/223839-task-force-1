<?php

namespace Logic\FileSystem\Managers\FileManager;

use Exceptions\FileSystem\FileDoesNotExistsException;
use Exceptions\FileSystem\FileExistsException;
use Exceptions\FileSystem\ThisIsNotFileException;

class FileManager
{

    public function openFile(string $path, string $mode = 'r')
    {
        if (file_exists($path)) {
            throw new FileDoesNotExistsException();
        }

        return fopen($path, $mode);
    }

    public function closeFile($fileHandler): void
    {
        if ( ! is_resource($fileHandler)) {
            throw new ThisIsNotFileException();
        }
        fclose($fileHandler);
    }

    public function createFile(string $path): void
    {

        if (file_exists($path)) {
            throw new FileExistsException();
        }
        $fhandler = fopen($path, 'w');
        $this->closeFile($fhandler);
    }

    public function saveFile($handler, string $data): void
    {
        if ( ! is_resource($handler)) {
            throw new ThisIsNotFileException();
        }

        fwrite($handler, $data);
    }

    public function deleteFile(string $pathDelete): void
    {
        if ( ! file_exists($pathDelete)) {
            throw new FileDoesNotExistsException();
        }
        if ( ! is_file($pathDelete)) {
            throw new ThisIsNotFileException();
        }

        unlink($pathDelete);
    }

    public function checkExistFile(string $filePath): bool
    {
        if ( ! file_exists($filePath)) {
            return false;
        }
        if ( ! is_file($filePath)) {
            throw new ThisIsNotFileException();
        }

        return true;
    }

    public function getHandlerName(string $path): array
    {
        $handler          = $this->initSplFileObject($path);
        $handlerName      = $handler->getFilename();
        $handlerExtension = $handler->getExtension();
        $handlerName      = str_replace(".{$handlerExtension}", '', $handlerName);

        return [$handlerName, $handlerExtension];
    }

    private function initSplFileObject($path)
    {
        return new \SplFileObject($path);
    }
}

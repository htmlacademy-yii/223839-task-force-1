<?php

namespace Logic\FileSystem\Managers\FileManager;

use Logic\FileSystem\Managers\FileManager\Actions\ActionCheckFileExists;
use Logic\FileSystem\Managers\FileManager\Actions\ActionDeleteFile;
use Logic\FileSystem\Managers\FileManager\Actions\ActionOpenFile;
use Logic\FileSystem\Managers\FileManager\Actions\ActionCloseFile;
use Logic\FileSystem\Managers\FileManager\Actions\ActionCreateFile;
use Logic\FileSystem\Managers\FileManager\Actions\ActionSaveFile;

class FileManager
{

    public function openFile(string $path, string $mode = 'r')
    {
        $action = new ActionOpenFile($path);
        return $action->openFile($mode);
    }

    public function closeFile($fileHandler): void
    {
        $action = new ActionCloseFile();
        $action->closeFile($fileHandler);
    }

    public function createFile(string $path): void
    {

        $action = new ActionCreateFile($path);
        $action->createFile();
    }

    public function saveFile($handler, string $data): void
    {
        $action = new ActionSaveFile($data);
        $action->saveFile($handler);
    }

    public function deleteFile(string $pathDelete): void
    {
        $action = new ActionDeleteFile($pathDelete);
        $action->deleteFile();
    }

    public function checkExistFile(string $filePath): bool
    {
        $action = new ActionCheckFileExists($filePath);

        return $action->checkFileExists();
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

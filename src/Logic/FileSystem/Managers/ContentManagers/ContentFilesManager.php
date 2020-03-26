<?php

namespace Logic\FileSystem\Managers\SaveContentManagers;

use Logic\FileSystem\Managers\FileManager\FileManager;
use Logic\FileSystem\Managers\IContentManager;

class ContentFilesManager implements IContentManager
{
    private FileManager $fileManager;

    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    public function saveContent(string $savePath, string $content): void
    {
        if ($this->fileManager->checkExistFile($savePath)) {
            $this->fileManager->deleteFile($savePath);
        }
        $handler = $this->fileManager->openFile($savePath, 'w');

        $this->fileManager->saveFile($handler, $content);

        $this->fileManager->closeFile($handler);
    }

    public function getContent(string $path): string
    {
        // TODO: Implement getContent() method.
    }
}

<?php

namespace Logic\FileSystem\Managers;

/**
 * Interface IContentManager
 *
 * Интерфейс для управления контентом разных систем
 * fileSystem, databaseSystem...
 *
 * @package Logic\FileSystem\Managers
 */
interface IContentManager extends IManager
{
    public function saveContent(string $content, string $savePath): void;

    public function getContent(string $path): string;
}

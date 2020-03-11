<?php


namespace Logic\convertors\File\Interfaces;


interface FileInterface
{
    public function createFile(string $path);
    public function getFile();
    public function getFileInfo();
    public function saveFile(): void;

}

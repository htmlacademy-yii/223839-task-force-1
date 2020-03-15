<?php


namespace Logic\FileSystem;


use Logic\FileSystem\DirActions\DirActionRead;
use Logic\FileSystem\FileActions\FileActionOpen;
use Logic\FileSystem\FileActions\FileActionRead;

class Reader
{
    /////////////////////////////////////////////////////////
    //////////////         FILES            /////////////////
    /////////////////////////////////////////////////////////

    public function openFile(string $path): \SplFileObject
    {
        return $this->getFileOpener($path)->openFile();
    }

    public function getFileContent(\SplFileObject $file): array
    {
        return $this->getFileReader($file)->getFileContent();
    }

    public function getFileContentElement(\SplFileObject $file, int $index): array
    {
        return $this->getFileReader($file)->getContentElement($index);
    }

    public function getFileNameWithoutExtension(\SplFileObject $file): string
    {
        $fileExtension = $file->getExtension();
        return str_replace(".{$fileExtension}", '', $file->getFilename());
    }

    /**
     * @return array]SplFileObject]
     */
    public function getSPLFilesObjectsGroup(string $path): array
    {
        foreach ($this->getFilesInDirectory($path) as $pathToFile) {
            $filesGroup[] = $this->openFile($pathToFile);
        }
        return $filesGroup;
    }

    /////////////////////////////////////////////////////////
    //////////////         DIRECTORY       //////////////////
    /////////////////////////////////////////////////////////

    public function getFilesInDirectory($path)
    {
        return $this->getDirectoryReader()->getFilesInDirectory($path);
    }




    private function getFileOpener(string $path) : FileActionOpen
    {
        return new FileActionOpen($path);
    }

    private function getFileReader(\SplFileObject $file) :FileActionRead
    {
        return new FileActionRead($file);
    }

    private function getDirectoryReader() : DirActionRead
    {
        return new DirActionRead();
    }

}

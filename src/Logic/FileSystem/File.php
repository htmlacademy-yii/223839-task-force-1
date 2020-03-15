<?php


namespace Logic\FileSystem;


use Logic\FileSystem\interfaces\FileInterface;

class File implements FileInterface
{
    public function __construct(string $path)
    {
        $this->path = $path;
        return $this->getFile();
    }

    private function getFile(): \SplFileObject
    {
        return new \SplFileObject($this->path);
    }
}

//class Filees implements FileInterface
//{
//    private string $filePath;
//    private \SplFileObject $file;
//
//    public function __construct(string $path)
//    {
//        $this->filePath = $path;
//        $this->file = $this->getFile();
//    }
//
//    public function createFile(string $path)
//    {
//        if (file_exists($path)) {
//            throw new FileExistsException();
//        }
//        if (!file_exists($path)) {
//            $saveFile = fopen($path, 'w');
//            fwrite($saveFile, '');
//            fclose($saveFile);
//        }
//    }
//
//    public function getFile(): \SplFileObject
//    {
//        if (!file_exists($this->filePath)) {
//            throw new FileDoesNotExistException();
//        }
//        return new \SplFileObject($this->filePath);
//    }
//
//    public function getFileInfo(): \SplFileInfo
//    {
//        if (!file_exists($this->filePath)) {
//            throw new FileDoesNotExistException();
//        }
//        return new \SplFileInfo($this->filePath);
//    }
//
//    public function getFileNameWithoutExtension(): string
//    {
//        $fileDescriptor = $this->getFile()->getExtension();
//        $cutDescriptorPattern = '/.' . $fileDescriptor . '/xi';
//        return preg_replace(
//            $cutDescriptorPattern,
//            '',
//            $this->file->getFileInfo()->getFilename()
//        );
//    }
//
//    public function saveFile(): void
//    {
//        // TODO: Implement saveFile() method.
//    }
//}

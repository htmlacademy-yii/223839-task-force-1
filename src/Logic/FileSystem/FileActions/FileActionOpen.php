<?php


namespace Logic\FileSystem\FileActions;


use src\exceptions\fileSystem\FileDoesNotExistsException;
use src\exceptions\fileSystem\FileNotAvailableWritable;
use src\exceptions\fileSystem\ThisIsNotFile;
use src\exceptions\BaseException;

class FileActionOpen implements IFileActions
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
        try {
            $this->openFile();
        } catch (BaseException $err) {
            echo $err->getMessage();
        }
    }

    public function openFile() : \SplFileObject
    {
        if (!file_exists($this->path)) {
            throw new FileDoesNotExistsException();
        }
        if (!is_file(($this->path))) {
            throw new ThisIsNotFile();
        }
        if (!is_writable($this->path)) {
            throw new FileNotAvailableWritable();
        }
        return new \SplFileObject($this->path);
    }


    protected function checkRights(int $customerID, int $performerID, int $currentUserID): bool
    {
        // TODO: Implement checkRights() method.
    }
}

<?php


namespace Logic\FileSystem\FileActions;


class FileActionRead implements IFileActions
{
    private \SplFileObject $file;
    private array $content = [];

    public function __construct(\SplFileObject $file)
    {
        $this->file = $file;
    }

    public function getFileContent(): array
    {
        foreach ($this->getFileContentGenerator() as $arr) {
            $this->content[] = $arr;
        }
        return $this->content;
    }

    private function getFileContentGenerator(): \Generator
    {
        while ($this->file->valid()) {
            yield $this->file->fgetcsv();
        }
    }

    public function getContentElement($index): array
    {
        return $this->getFileContent()[$index];
    }
}

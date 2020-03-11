<?php


namespace Logic\convertors\File;


use Logic\convertors\File\interfaces\ContentInterfaces;

class Content implements ContentInterfaces
{
    public function __construct(\SplFileObject $file)
    {
        $this->file = $file;
    }

    public function getContentGenerator(): \Generator
    {
        while ($this->file->valid()) {
            yield $this->file->fgetcsv();
        }
    }

    public function generateContent(): array
    {
        $content = [];
        foreach ($this->getContentGenerator() as $string) {
            array_push($content, $string);
        }
        return $content;
    }
}

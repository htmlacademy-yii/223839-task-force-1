<?php

namespace Logic\FileSystem\Data\CSV;

use Logic\FileSystem\Data\IDTO;

class CSVDTO implements IDTO
{
    private array $content;
    private string $fileName;

    public function __construct(array $content, string $fileName)
    {
        $this->content  = $content;
        $this->fileName = $fileName;
    }

    public function getContent(): array
    {
        return $this->content;
    }

    public function setContent(array $content): void
    {
        $this->content = $content;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }
}

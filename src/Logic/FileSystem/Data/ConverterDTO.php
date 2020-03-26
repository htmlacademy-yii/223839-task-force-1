<?php


namespace Logic\FileSystem\Converter;


use Exceptions\FileSystem\KeyNotExistException;
use Logic\FileSystem\Data\IDTO;
use Logic\FileSystem\Managers\IContentManager;
use Logic\FileSystem\Managers\IReader;
use Logic\FileSystem\Managers\IWriter;

class ConverterDTO implements IDTO
{
    public IReader $reader;
    public IWriter $writer;
    public IContentManager $contentManager;

    private array $data = [];

    public function __construct(IReader $reader, IWriter $writer, IContentManager $contentManager)
    {
        $this->reader         = $reader;
        $this->writer         = $writer;
        $this->contentManager = $contentManager;
    }

    public function __set($name, $value): void
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if ( ! array_key_exists($name, $this->data)) {
            throw new KeyNotExistException();
        }

        return $this->data[$name];
    }
}

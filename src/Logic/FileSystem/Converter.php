<?php

namespace Logic\FileSystem;

use Logic\FileSystem\Data\IDTO;
use Logic\FileSystem\Managers\IWriter;

class Converter
{
    private IDTO $DTO;
    private IWriter $writer;

    public function converting(IDTO $DTO, IWriter $writer): string
    {
        $this->DTO    = $DTO;
        $this->writer = $writer;

        return $this->writer->getWriteData($this->DTO);
    }
}

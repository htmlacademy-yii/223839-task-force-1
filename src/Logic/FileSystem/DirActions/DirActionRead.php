<?php


namespace Logic\FileSystem\DirActions;


class DirActionRead
{
    public function getFilesInDirectory($path)
    {
        return glob($path . '/*');
    }
}

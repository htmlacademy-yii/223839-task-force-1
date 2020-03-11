<?php


namespace Logic\convertors\File\interfaces;


interface ContentInterfaces
{
    public function getContentGenerator(): \Generator;

    public function generateContent(): array;
}

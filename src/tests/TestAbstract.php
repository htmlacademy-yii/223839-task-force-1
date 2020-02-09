<?php


namespace src\tests;


abstract class TestAbstract
{
    public static function getName()
    {
        return static::class;
    }
}

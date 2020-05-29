<?php

namespace frontend\tests;

use frontend\modules\WordsTerminations;

class WordTerminationsFeatureTest extends \Codeception\Test\Unit
{
    /**
     * @var \frontend\tests\UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testRight()
    {
        $terminations = [
          0 => 'заказов',
          1 => 'заказ',
          2 => 'заказа',
          5 => 'заказов'
        ];
        $this->assertSame('заказов', WordsTerminations::getWordTermination(20, $terminations));
        $this->assertSame('заказов', WordsTerminations::getWordTermination(11, $terminations));
        $this->assertSame('заказов', WordsTerminations::getWordTermination(12, $terminations));
        $this->assertSame('заказов', WordsTerminations::getWordTermination(13, $terminations));
        $this->assertSame('заказов', WordsTerminations::getWordTermination(14, $terminations));
        $this->assertSame('заказов', WordsTerminations::getWordTermination(15, $terminations));
        $this->assertSame('заказ', WordsTerminations::getWordTermination(21, $terminations));
        $this->assertSame('заказа', WordsTerminations::getWordTermination(22, $terminations));

        $terminations = [
          0 => 'тестов',
          1 => 'тест',
          2 => 'теста',
          5 => 'тестов'
        ];
        $this->assertSame('тестов', WordsTerminations::getWordTermination(0, $terminations));
        $this->assertSame('тест', WordsTerminations::getWordTermination(1, $terminations));
        $this->assertSame('теста', WordsTerminations::getWordTermination(2, $terminations));
        $this->assertSame('теста', WordsTerminations::getWordTermination(3, $terminations));
        $this->assertSame('теста', WordsTerminations::getWordTermination(4, $terminations));
        $this->assertSame('тестов', WordsTerminations::getWordTermination(5, $terminations));

        $terminations = [
          0 => '255MB',
          1 => 244,
          2 => 23,
          5 => '234'
        ];
        $this->assertSame('255MB', WordsTerminations::getWordTermination(0, $terminations));
        $this->assertSame('244', WordsTerminations::getWordTermination(1, $terminations));
        $this->assertSame('23', WordsTerminations::getWordTermination(2, $terminations));
        $this->assertSame('23', WordsTerminations::getWordTermination(3, $terminations));
        $this->assertSame('23', WordsTerminations::getWordTermination(4, $terminations));
        $this->assertSame('234', WordsTerminations::getWordTermination(5, $terminations));
    }
}

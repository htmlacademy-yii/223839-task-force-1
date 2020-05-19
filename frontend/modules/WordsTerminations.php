<?php

namespace frontend\modules;

/**
 * Class WordsTerminations
 *
 * Change the end of the word depending on the counter
 *
 * @package frontend\modules
 */
class WordsTerminations
{
    /**
     * $terminations = [
     * 0 => 'ов',
     * 1 => '',
     * 2 => 'а',
     * 5 => 'ов'
     * ];
     *
     * @param $counter
     * @param $terminations
     * @return string
     */
    public static function getWordTermination($counter, $terminations): string
    {
        $counter = mb_substr($counter, -2);
        if ($counter >= 10 and $counter <= 15) {
            return $terminations[0];
        } else {
            $counter = substr($counter, -1);

            $termination = '';

            if ($counter == 0) {
                $termination = $terminations[0];
            } elseif ($counter == 1) {
                $termination = $terminations[1];
            } elseif ($counter >= 2 && $counter <= 4) {
                $termination = $terminations[2];
            } elseif ($counter >= 5) {
                $termination = $terminations[5];
            }
            return $termination;
        }
    }
}

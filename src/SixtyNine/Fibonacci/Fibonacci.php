<?php
namespace SixtyNine\Fibonacci;
/**
 * Fibonnaci numbers linear version.
 */
class Fibonacci
{
    public function calc(int $number) : int
    {
        $i = 0;
        $j = 1;
        for ($k = 0; $k < $number; $k++) {
            $tmp = $i + $j;
            $i = $j;
            $j = $tmp;
        }
        return $i;
    }
}

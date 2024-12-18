<?php
namespace App\Service;

// TODO move to bundle
class BitReader
{
    /**
     * Get bit position in int number
    */
    public static function get(int $n, int $bitPos) : int
    {
        $bitInInt = 64;
        $shiftSz = $bitInInt - $bitPos - 1;
        $n = $n << $shiftSz;
        if ($n < 0) {
            return 1;
        }
        $n = $n >> ($bitInInt -  $bitPos + 1);

        return $n;
    }
}
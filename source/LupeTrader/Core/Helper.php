<?php

namespace LupeCode\phpTraderNative\LupeTrader\Core;

class Helper
{
    public static function adjustArrayOffset(array $inputArray, int $newStartingIndex)
    {
        $outputArray = [];
        $inputArray  = array_values($inputArray);
        foreach ($inputArray as $index => $value) {
            $outputArray[$index + $newStartingIndex] = $value;
        }

        return $outputArray;
    }
}

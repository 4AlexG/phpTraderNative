<?php

namespace LupeCode\phpTraderNative;

use LupeCode\phpTraderNative\LupeTrader\MomentumIndicators\RSI;
use LupeCode\phpTraderNative\TALib\Enum\MovingAverageType;
use LupeCode\phpTraderNative\TALib\Enum\ReturnCode;

class LupeTrader extends Trader
{

    /**
     * Relative Strength Index
     *
     * @param array $real       Array of real values.
     * @param int   $timePeriod [OPTIONAL] [DEFAULT 14, SUGGESTED 4-200] Number of period. Valid range from 2 to 100000.
     *
     * @return array Returns an array with calculated data.
     * @throws \Exception
     */
    public static function rsi(array $real, int $timePeriod = 14): array
    {
        return RSI::rsi($real, $timePeriod);
    }

    /**
     * Slow Stochastic Relative Strength Index
     *
     * @param array $real        Array of real values.
     * @param int   $rsiPeriod   [OPTIONAL] [DEFAULT 14, SUGGESTED 4-200] Number of period. Valid range from 2 to 100000.
     * @param int   $fastKPeriod [OPTIONAL] [DEFAULT 5, SUGGESTED 1-200] Time period for building the Fast-K line. Valid range from 1 to 100000.
     * @param int   $slowKPeriod [OPTIONAL] [DEFAULT 3, SUGGESTED 1-200] Smoothing for making the Slow-K line. Valid range from 1 to 100000, usually set to 3.
     * @param int   $slowKMAType [OPTIONAL] [DEFAULT TRADER_MA_TYPE_SMA] Type of Moving Average for Slow-K. MovingAverageType::* series of constants should be used.
     * @param int   $slowDPeriod [OPTIONAL] [DEFAULT 3, SUGGESTED 1-200] Smoothing for making the Slow-D line. Valid range from 1 to 100000.
     * @param int   $slowDMAType [OPTIONAL] [DEFAULT TRADER_MA_TYPE_SMA] Type of Moving Average for Slow-D. MovingAverageType::* series of constants should be used.
     *
     * @return array Returns an array with calculated data. [SlowK => [...], SlowD => [...]]
     * @throws \Exception
     */
    public static function slowstochrsi(array $real, int $rsiPeriod = 14, int $fastKPeriod = 5, int $slowKPeriod = 3, int $slowKMAType = MovingAverageType::SMA, int $slowDPeriod = 3, int $slowDMAType = MovingAverageType::SMA): array
    {
        $real     = \array_values($real);
        $endIdx   = count($real) - 1;
        $rsi      = RSI::rsi($real, $rsiPeriod);
        $rsi      = array_values($rsi);
        $endIdx   = self::verifyArrayCounts([&$rsi]);
        $outSlowK = [];
        $outSlowD = [];
        self::checkForError(self::getMomentumIndicators()::stoch(0, $endIdx, $rsi, $rsi, $rsi, $fastKPeriod, $slowKPeriod, $slowKMAType, $slowDPeriod, $slowDMAType, self::$outBegIdx, self::$outNBElement, $outSlowK, $outSlowD));

        return [
            'SlowK' => self::adjustIndexes($outSlowK, self::$outBegIdx),
            'SlowD' => self::adjustIndexes($outSlowD, self::$outBegIdx),
        ];
    }
}

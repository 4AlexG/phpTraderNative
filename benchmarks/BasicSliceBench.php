<?php

use LupeCode\phpTraderNative\LupeTrader\Core\Exception;
use LupeCode\phpTraderNative\LupeTrader\MomentumIndicators\FastStochastic;
use LupeCode\phpTraderNative\LupeTrader\OverlapStudies\MovingAverage;
use LupeCode\phpTraderNative\Trader;

/**
 * @Revs({10240})
 * @Iterations(1)
 */
class BasicSliceBench
{
    use \LupeCode\phpTraderNativeTest\TestingTrait;

    /** @var FastStochastic */
    private $y = 4;

    public function benchArraySlice()
    {
        $limit = count($this->High) - 1 - $this->y;
        for ($i = 0; $i < $limit; $i++) {
            $array = array_slice($this->High, $i, $this->y);
        }
    }

    public function benchLoopSlice()
    {
        $limit = count($this->High) - 1 - $this->y;
        for ($i = 0; $i < $limit; $i++) {
            $array = [];
            for ($j = 0; $j < $this->y; $j++) {
                $array[] = $this->High[$i + $j];
            }
        }
    }
}

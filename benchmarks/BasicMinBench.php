<?php

use LupeCode\phpTraderNative\LupeTrader\Core\Exception;
use LupeCode\phpTraderNative\LupeTrader\MomentumIndicators\FastStochastic;
use LupeCode\phpTraderNative\LupeTrader\OverlapStudies\MovingAverage;
use LupeCode\phpTraderNative\Trader;

/**
 * @Revs({10240})
 * @Iterations(1)
 */
class BasicMinBench
{
    use \LupeCode\phpTraderNativeTest\TestingTrait;

    /** @var FastStochastic */
    private $y = 2;

    public function benchArrayMin()
    {
        $x = min($this->y, 0);
    }

    public function benchTernaryMin()
    {
        $x = $this->y < 0 ? $this->y : 0;
    }
}

<?php

use LupeCode\phpTraderNative\LupeTrader\Core\Exception;
use LupeCode\phpTraderNative\LupeTrader\MomentumIndicators\RelativeStrengthIndex;
use LupeCode\phpTraderNative\Trader;

/**
 * @Revs({1024})
 * @BeforeMethods({"init"})
 * @Iterations(1)
 */
class RelativeStrengthIndexBench
{
    use \LupeCode\phpTraderNativeTest\TestingTrait;

    /** @var RelativeStrengthIndex */
    private $rsi;

    public function init()
    {
        $optInTimePeriod = 10;
        $this->rsi       = new RelativeStrengthIndex();
        $this->rsi->setPeriod($optInTimePeriod)->setInputArray($this->High);
    }

    /**
     * @throws Exception
     */
    public function benchLupeTrader()
    {
        $optInTimePeriod = 10;
        $rsi       = new RelativeStrengthIndex();
        $rsi->setPeriod($optInTimePeriod)->setInputArray($this->High)->calculate();
    }

    /**
     * @throws Exception
     */
    public function benchLupeTraderReuse()
    {
        $this->rsi->calculate();
    }

    /**
     * @throws \Exception
     */
    public function benchTraderStatic()
    {
        $optInTimePeriod = 10;
        Trader::rsi($this->High,$optInTimePeriod);
    }

    public function benchTaLib()
    {
        $optInTimePeriod = 10;
        \trader_rsi($this->High, $optInTimePeriod);
    }
}

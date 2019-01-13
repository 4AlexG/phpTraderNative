<?php

use LupeCode\phpTraderNative\LupeTrader\Core\Exception;
use LupeCode\phpTraderNative\LupeTrader\OverlapStudies\SimpleMovingAverage;
use LupeCode\phpTraderNative\Trader;

/**
 * @Revs({1024})
 * @BeforeMethods({"init"})
 * @Iterations(1)
 */
class SimpleMovingAverageBench
{
    use \LupeCode\phpTraderNativeTest\TestingTrait;

    /** @var SimpleMovingAverage*/
    private $sma;

    public function init()
    {
        $optInTimePeriod = 10;
        $this->sma       = new SimpleMovingAverage();
        $this->sma->setPeriod($optInTimePeriod)->setInputArray($this->High);
    }

    /**
     * @throws Exception
     */
    public function benchLupeTrader()
    {
        $optInTimePeriod = 10;
        $rsi             = new SimpleMovingAverage();
        $rsi->setPeriod($optInTimePeriod)->setInputArray($this->High)->calculate();
    }

    /**
     * @throws Exception
     */
    public function benchLupeTraderReuse()
    {
        $this->sma->calculate();
    }

    /**
     * @throws \Exception
     */
    public function benchTraderStatic()
    {
        $optInTimePeriod = 10;
        Trader::sma($this->High, $optInTimePeriod);
    }

    public function benchTaLib()
    {
        $optInTimePeriod = 10;
        \trader_sma($this->High, $optInTimePeriod);
    }
}

<?php

use LupeCode\phpTraderNative\LupeTrader\Core\Exception;
use LupeCode\phpTraderNative\LupeTrader\MomentumIndicators\FastStochastic;
use LupeCode\phpTraderNative\LupeTrader\OverlapStudies\MovingAverage;
use LupeCode\phpTraderNative\Trader;

/**
 * @Revs({1024})
 * @BeforeMethods({"init"})
 * @Iterations(1)
 */
class FastStochasticBench
{
    use \LupeCode\phpTraderNativeTest\TestingTrait;

    /** @var FastStochastic */
    private $fast;

    public function init()
    {
        $optInFastK_Period = 14;
        $optInFastD_Period = 3;
        $optInFastD_MAType = MovingAverage::SIMPLE_MOVING_AVERAGE;
        $this->fast = new FastStochastic();
        $this->fast
            ->setInputHigh($this->High)
            ->setInputLow($this->Low)
            ->setInputClose($this->Close)
            ->setInputFastKPeriod($optInFastK_Period)
            ->setInputFastDPeriod($optInFastD_Period)
            ->setInputMovingAverageType($optInFastD_MAType)
            ;
    }

    /**
     * @throws Exception
     */
    public function benchLupeTrader()
    {
        $optInFastK_Period = 14;
        $optInFastD_Period = 3;
        $optInFastD_MAType = MovingAverage::SIMPLE_MOVING_AVERAGE;
        $fast = new FastStochastic();
        $fast
            ->setInputHigh($this->High)
            ->setInputLow($this->Low)
            ->setInputClose($this->Close)
            ->setInputFastKPeriod($optInFastK_Period)
            ->setInputFastDPeriod($optInFastD_Period)
            ->setInputMovingAverageType($optInFastD_MAType)
            ->calculate()
        ;
    }

    /**
     * @throws Exception
     */
    public function benchLupeTraderReuse()
    {
        $this->fast->calculate();
    }

    /**
     * @throws \Exception
     */
    public function benchTraderStatic()
    {
        $optInFastK_Period = 14;
        $optInFastD_Period = 3;
        $optInFastD_MAType = MovingAverage::SIMPLE_MOVING_AVERAGE;
        Trader::stochf($this->High, $this->Low, $this->Close, $optInFastK_Period, $optInFastD_Period, $optInFastD_MAType);
    }

    public function benchTaLib()
    {
        $optInFastK_Period = 14;
        $optInFastD_Period = 3;
        $optInFastD_MAType = MovingAverage::SIMPLE_MOVING_AVERAGE;
        \trader_stochf($this->High, $this->Low, $this->Close, $optInFastK_Period, $optInFastD_Period, $optInFastD_MAType);
    }
}

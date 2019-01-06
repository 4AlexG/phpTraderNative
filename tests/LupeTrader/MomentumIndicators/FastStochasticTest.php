<?php

namespace LupeCode\phpTraderNativeTest\LupeTrader\MomentumIndicators;

use LupeCode\phpTraderNative\LupeTrader\Core\Helper;
use LupeCode\phpTraderNative\LupeTrader\MomentumIndicators\FastStochastic;
use LupeCode\phpTraderNative\LupeTrader\OverlapStudies\MovingAverage;
use LupeCode\phpTraderNativeTest\TestingTrait;
use PHPUnit\Framework\TestCase;

class FastStochasticTest extends TestCase
{
    use TestingTrait;

    /**
     * @throws \LupeCode\phpTraderNative\LupeTrader\Core\Exception
     */
    public function testCalculate()
    {
        $optInFastK_Period = 14;
        $optInFastD_Period = 3;
        $optInFastD_MAType = MovingAverage::SIMPLE_MOVING_AVERAGE;
        list($traderFastK, $traderFastD) = \trader_stochf($this->High, $this->Low, $this->Close, $optInFastK_Period, $optInFastD_Period, $optInFastD_MAType);
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
        $Output      = [
            'FastK' => array_slice($fast->getOutputFastK(), $optInFastD_Period - 1, null, true),
            'FastD' => Helper::adjustArrayOffset($fast->getOutputFastD(), $optInFastK_Period + $optInFastD_Period - 2),
        ];
        $traderFastD = array_slice($traderFastD, 0, -1, true);
        $this->assertEquals($traderFastK, $this->adjustForPECL($Output['FastK']));
        $this->assertEquals($traderFastD, $this->adjustForPECL($Output['FastD']));

    }
}

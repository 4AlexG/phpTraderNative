<?php

namespace LupeCode\phpTraderNativeTest\LupeTrader\MomentumIndicators;

use LupeCode\phpTraderNative\LupeTrader\Core\Exception;
use LupeCode\phpTraderNative\LupeTrader\Core\Helper;
use LupeCode\phpTraderNative\LupeTrader\MomentumIndicators\SlowStochastic;
use LupeCode\phpTraderNative\LupeTrader\OverlapStudies\MovingAverage;
use LupeCode\phpTraderNativeTest\TestingTrait;
use PHPUnit\Framework\TestCase;

class SlowStochasticTest extends TestCase
{
    use TestingTrait;

    /**
     * @throws Exception
     */
    public function testCalculate()
    {
        $optInFastK_Period = 14;
        $optInSlowK_Period = 3;
        $optInSlowK_MAType = MovingAverage::SIMPLE_MOVING_AVERAGE;
        $optInSlowD_Period = 3;
        $optInSlowD_MAType = MovingAverage::SIMPLE_MOVING_AVERAGE;
        list($traderSlowK, $traderSlowD) = \trader_stoch($this->High, $this->Low, $this->Close, $optInFastK_Period, $optInSlowK_Period, $optInSlowK_MAType, $optInSlowD_Period, $optInSlowD_MAType);
        $slow = new SlowStochastic();
        $slow
            ->setInputHigh($this->High)
            ->setInputLow($this->Low)
            ->setInputClose($this->Close)
            ->setInputFastKPeriod($optInFastK_Period)
            ->setInputSlowKPeriod($optInSlowK_Period)
            ->setInputSlowDPeriod($optInSlowD_Period)
            ->setInputKMovingAverageType($optInSlowK_MAType)
            ->setInputDMovingAverageType($optInSlowD_MAType)
            ->calculate()
        ;
        $Output = [
            'SlowK' => Helper::adjustArrayOffset(
                array_slice($slow->getOutputSlowK(), $optInSlowK_Period - 1, null, true),
                $optInFastK_Period + $optInSlowK_Period
            ),
            'SlowD' => Helper::adjustArrayOffset(
                array_slice($slow->getOutputSlowD(), $optInSlowD_Period - 1, null, true),
                $optInSlowD_Period - 1
            ),
        ];
        $this->assertEquals($traderSlowK, $Output['SlowK'], '', 0.01);
        //Because the slow k has been modified, we need to recalculate the slow d
        $traderSlowD = (new MovingAverage())
            ->setInputMovingAverageType(MovingAverage::SIMPLE_MOVING_AVERAGE)
            ->setInputMovingAveragePeriod($optInSlowD_Period)
            ->setInputArray($traderSlowK)
            ->calculate()
            ->getOutputArray()
        ;
        $this->assertEquals($traderSlowD, $Output['SlowD'], '', 0.01);

    }
}

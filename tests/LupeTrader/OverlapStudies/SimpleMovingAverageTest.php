<?php

namespace LupeCode\phpTraderNativeTest\LupeTrader\OverlapStudies;

use LupeCode\phpTraderNative\LupeTrader\Core\Exception;
use LupeCode\phpTraderNative\LupeTrader\OverlapStudies\SimpleMovingAverage;
use LupeCode\phpTraderNativeTest\TestingTrait;
use PHPUnit\Framework\TestCase;

class SimpleMovingAverageTest extends TestCase
{
    use TestingTrait;

    /**
     * @throws Exception
     */
    public function testCalculate(): void
    {
        $optInTimePeriod = 10;
        $traderSMA = \trader_sma($this->High, $optInTimePeriod);
        $lupeSMA = (new SimpleMovingAverage())->setInputArray($this->High)->setPeriod($optInTimePeriod)->calculate()->getOutputArray();
        $this->assertEquals($traderSMA, $lupeSMA, '', 0.01);
    }

    /**
     * @throws Exception
     * @group exceptions
     */
    public function testCalculateException(): void
    {
        $simpleMovingAverageIndex = new SimpleMovingAverage();
        $this->expectException(Exception::class);
        $this->expectExceptionCode(Exception::INPUT_PARAMETERS_MISSING_CODE);
        $this->expectExceptionMessage(Exception::INPUT_PARAMETERS_MISSING_MESSAGE);
        $simpleMovingAverageIndex->calculate();
    }

    /**
     * @throws Exception
     */
    public function testCalculateTooShort(): void
    {
        $simpleMovingAverageIndex = new SimpleMovingAverage();
        $expected              = [];
        $simpleMovingAverageIndex
            ->setPeriod(14)
            ->setInputArray([1])
            ->calculate()
        ;
        $this->assertEquals($expected, $simpleMovingAverageIndex->getOutputArray());
    }

}

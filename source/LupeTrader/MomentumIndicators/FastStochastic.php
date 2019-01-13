<?php

namespace LupeCode\phpTraderNative\LupeTrader\MomentumIndicators;

use LupeCode\phpTraderNative\LupeTrader\Core\Calculation;
use LupeCode\phpTraderNative\LupeTrader\Core\Exception;
use LupeCode\phpTraderNative\LupeTrader\Core\Helper;
use LupeCode\phpTraderNative\LupeTrader\OverlapStudies\MovingAverage;

class FastStochastic implements Calculation
{

    const DEFAULT_FAST_K_PERIODS      = 14;
    const DEFAULT_FAST_D_PERIODS      = 3;
    const DEFAULT_MOVING_AVERAGE_TYPE = MovingAverage::SIMPLE_MOVING_AVERAGE;

    /** @var array */
    protected $inputClose;
    /** @var array */
    protected $inputHigh;
    /** @var array */
    protected $inputLow;
    /** @var int */
    protected $inputFastKPeriod = self::DEFAULT_FAST_K_PERIODS;
    /** @var int */
    protected $inputFastDPeriod = self::DEFAULT_FAST_D_PERIODS;
    /** @var int */
    protected $inputMovingAverageType = self::DEFAULT_MOVING_AVERAGE_TYPE;
    /** @var array */
    protected $outputFastK;
    /** @var array */
    protected $outputFastD;

    protected $movingAverage;

    /**
     * @param array $inputClose
     *
     * @return FastStochastic
     */
    public function setInputClose(array $inputClose)
    {
        $this->inputClose = array_values($inputClose);

        return $this;
    }

    /**
     * @param array $inputHigh
     *
     * @return FastStochastic
     */
    public function setInputHigh(array $inputHigh)
    {
        $this->inputHigh = array_values($inputHigh);

        return $this;
    }

    /**
     * @param array $inputLow
     *
     * @return FastStochastic
     */
    public function setInputLow(array $inputLow)
    {
        $this->inputLow = array_values($inputLow);

        return $this;
    }

    /**
     * @param int $inputFastKPeriod
     *
     * @return FastStochastic
     */
    public function setInputFastKPeriod(int $inputFastKPeriod)
    {
        $this->inputFastKPeriod = $inputFastKPeriod;

        return $this;
    }

    /**
     * @param int $inputFastDPeriod
     *
     * @return FastStochastic
     */
    public function setInputFastDPeriod(int $inputFastDPeriod)
    {
        $this->inputFastDPeriod = $inputFastDPeriod;

        return $this;
    }

    /**
     * @param int $inputMovingAverageType
     *
     * @return FastStochastic
     */
    public function setInputMovingAverageType(int $inputMovingAverageType)
    {
        $this->inputMovingAverageType = $inputMovingAverageType;

        return $this;
    }

    /**
     * @return array
     */
    public function getOutputFastK()
    {
        return $this->outputFastK;
    }

    /**
     * @return array
     */
    public function getOutputFastD()
    {
        return $this->outputFastD;
    }

    /**
     * @return $this
     * @throws \LupeCode\phpTraderNative\LupeTrader\Core\Exception
     */
    public function calculate()
    {
        //%K = 100 [(C – L14) / (H14 – L14)]
        $this->outputFastD = [];
        $this->outputFastK = [];
        if (empty($this->inputHigh) || empty($this->inputLow) || empty($this->inputClose) || empty($this->inputFastKPeriod) || empty($this->inputFastDPeriod)) {
            throw new Exception(Exception::INPUT_PARAMETERS_MISSING_MESSAGE, Exception::INPUT_PARAMETERS_MISSING_CODE);
        }
        $count = count($this->inputClose);
        if ($count < $this->inputFastKPeriod) {
            return $this;
        }
        for ($iterator = $this->inputFastKPeriod - 1; $iterator < $count; $iterator++) {
            $offset      = $iterator - ($this->inputFastKPeriod - 1);
            $highestHigh = max(array_slice($this->inputHigh, $offset, $this->inputFastKPeriod));
            $lowestLow   = min(array_slice($this->inputLow, $offset, $this->inputFastKPeriod));
            $numerator   = $this->inputClose[$iterator] - $lowestLow;
            $denominator = $highestHigh - $lowestLow;
            if ($highestHigh === $lowestLow) {
                $this->outputFastK[$iterator] = 0;
            } else {
                $this->outputFastK[$iterator] = 100 * (($numerator) / ($denominator));
            }
        }
        if (empty($this->movingAverage)) {
            $this->movingAverage = new MovingAverage();
        }
        $this->outputFastD = $this
            ->movingAverage
            ->setInputArray($this->outputFastK)
            ->setInputMovingAveragePeriod($this->inputFastDPeriod)
            ->setInputMovingAverageType($this->inputMovingAverageType)
            ->calculate()
            ->getOutputArray()
        ;
        $this->outputFastD = Helper::adjustArrayOffset($this->outputFastD, $this->inputFastKPeriod + $this->inputFastDPeriod);

        return $this;
    }

}

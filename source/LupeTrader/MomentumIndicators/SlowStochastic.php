<?php

namespace LupeCode\phpTraderNative\LupeTrader\MomentumIndicators;

use LupeCode\phpTraderNative\LupeTrader\Core\Calculation;
use LupeCode\phpTraderNative\LupeTrader\Core\Exception;
use LupeCode\phpTraderNative\LupeTrader\Core\Helper;
use LupeCode\phpTraderNative\LupeTrader\OverlapStudies\MovingAverage;

class SlowStochastic implements Calculation
{

    const DEFAULT_FAST_K_PERIODS        = 14;
    const DEFAULT_SLOW_K_PERIODS        = 3;
    const DEFAULT_SLOW_D_PERIODS        = 3;
    const DEFAULT_K_MOVING_AVERAGE_TYPE = MovingAverage::SIMPLE_MOVING_AVERAGE;
    const DEFAULT_D_MOVING_AVERAGE_TYPE = MovingAverage::SIMPLE_MOVING_AVERAGE;

    /** @var array */
    protected $inputClose;
    /** @var array */
    protected $inputHigh;
    /** @var array */
    protected $inputLow;
    /** @var int */
    protected $inputFastKPeriod = self::DEFAULT_FAST_K_PERIODS;
    /** @var int */
    protected $inputSlowKPeriod = self::DEFAULT_SLOW_K_PERIODS;
    /** @var int */
    protected $inputSlowDPeriod = self::DEFAULT_SLOW_D_PERIODS;
    /** @var int */
    protected $inputKMovingAverageType = self::DEFAULT_K_MOVING_AVERAGE_TYPE;
    /** @var int */
    protected $inputDMovingAverageType = self::DEFAULT_D_MOVING_AVERAGE_TYPE;
    /** @var array */
    protected $outputSlowK;
    /** @var array */
    protected $outputSlowD;
    /** @var MovingAverage */
    protected $movingAverage;
    /** @var FastStochastic */
    protected $fastStochastic;

    /**
     * @param array $inputClose
     *
     * @return $this
     */
    public function setInputClose(array $inputClose)
    {
        $this->inputClose = array_values($inputClose);

        return $this;
    }

    /**
     * @param array $inputHigh
     *
     * @return $this
     */
    public function setInputHigh(array $inputHigh)
    {
        $this->inputHigh = array_values($inputHigh);

        return $this;
    }

    /**
     * @param array $inputLow
     *
     * @return $this
     */
    public function setInputLow(array $inputLow)
    {
        $this->inputLow = array_values($inputLow);

        return $this;
    }

    /**
     * @param int $inputFastKPeriod
     *
     * @return $this
     */
    public function setInputFastKPeriod(int $inputFastKPeriod)
    {
        $this->inputFastKPeriod = $inputFastKPeriod;

        return $this;
    }

    /**
     * @param int $inputSlowKPeriod
     *
     * @return $this
     */
    public function setInputSlowKPeriod(int $inputSlowKPeriod)
    {
        $this->inputSlowKPeriod = $inputSlowKPeriod;

        return $this;
    }

    /**
     * @param int $inputSlowDPeriod
     *
     * @return $this
     */
    public function setInputSlowDPeriod(int $inputSlowDPeriod)
    {
        $this->inputSlowDPeriod = $inputSlowDPeriod;

        return $this;
    }

    /**
     * @param int $inputKMovingAverageType
     *
     * @return $this
     */
    public function setInputKMovingAverageType(int $inputKMovingAverageType)
    {
        $this->inputKMovingAverageType = $inputKMovingAverageType;

        return $this;
    }

    /**
     * @param int $inputDMovingAverageType
     *
     * @return $this
     */
    public function setInputDMovingAverageType(int $inputDMovingAverageType)
    {
        $this->inputDMovingAverageType = $inputDMovingAverageType;

        return $this;
    }

    /**
     * @return array
     */
    public function getOutputSlowK()
    {
        return $this->outputSlowK;
    }

    /**
     * @return array
     */
    public function getOutputSlowD()
    {
        return $this->outputSlowD;
    }

    /**
     * @return $this
     * @throws \LupeCode\phpTraderNative\LupeTrader\Core\Exception
     */
    public function calculate()
    {
        $this->outputSlowD = [];
        $this->outputSlowK = [];
        if (
            empty($this->inputHigh) ||
            empty($this->inputLow) ||
            empty($this->inputClose) ||
            empty($this->inputFastKPeriod) ||
            empty($this->inputSlowKPeriod) ||
            empty($this->inputSlowDPeriod)
        ) {
            throw new Exception(Exception::INPUT_PARAMETERS_MISSING_MESSAGE, Exception::INPUT_PARAMETERS_MISSING_CODE);
        }
        $count = count($this->inputClose);
        if ($count < $this->inputFastKPeriod) {
            return $this;
        }
        if (empty($this->fastStochastic)) {
            $this->fastStochastic = new FastStochastic();
        }
        $this->fastStochastic
            ->setInputClose($this->inputClose)
            ->setInputHigh($this->inputHigh)
            ->setInputLow($this->inputLow)
            ->setInputFastKPeriod($this->inputFastKPeriod)
            ->setInputFastDPeriod($this->inputSlowKPeriod)
            ->setInputMovingAverageType($this->inputKMovingAverageType)
            ->calculate()
        ;
        $this->outputSlowK = $this->fastStochastic->getOutputFastD();
        if (empty($this->movingAverage)) {
            $this->movingAverage = new MovingAverage();
        }
        $this->outputSlowD = $this->movingAverage
            ->setInputArray($this->outputSlowK)
            ->setInputMovingAveragePeriod($this->inputSlowDPeriod)
            ->setInputMovingAverageType($this->inputDMovingAverageType)
            ->calculate()
            ->getOutputArray()
        ;
        $this->outputSlowD = Helper::adjustArrayOffset($this->outputSlowD, $this->inputSlowKPeriod + $this->inputSlowDPeriod);

        return $this;
    }

}

<?php

namespace LupeCode\phpTraderNative\LupeTrader\OverlapStudies;

use LupeCode\phpTraderNative\LupeTrader\Core\Calculation;
use LupeCode\phpTraderNative\LupeTrader\Core\Exception;

class MovingAverage implements Calculation
{

    /** @var int */
    const DEFAULT_PERIOD = 30;
    /** @var int */
    const DEFAULT_TYPE = self::SIMPLE_MOVING_AVERAGE;
    /** @var int */
    const SIMPLE_MOVING_AVERAGE = 0;
    /** @var int */
    const EXPONENTIAL_MOVING_AVERAGE = 1;
    /** @var int */
    const WEIGHTED_MOVING_AVERAGE = 2;
    /** @var int */
    const DOUBLE_EXPONENTIAL_MOVING_AVERAGE = 3;
    /** @var int */
    const TRIPLE_EXPONENTIAL_MOVING_AVERAGE = 4;
    /** @var int */
    const TRIANGULAR_MOVING_AVERAGE = 5;
    /** @var int */
    const KAUFMAN_ADAPTIVE_MOVING_AVERAGE = 6;
    /** @var int */
    const MESA_ADAPTIVE_MOVING_AVERAGE = 7;
    /** @var int */
    const T3_MOVING_AVERAGE = 8;

    /** @var int */
    protected $inputMovingAveragePeriod;
    /** @var int */
    protected $inputMovingAverageType = self::DEFAULT_PERIOD;
    /** @var array */
    protected $inputArray;
    /** @var array */
    protected $outputArray;

    protected $sma;

    /**
     * @param int $inputMovingAveragePeriod
     *
     * @return $this
     */
    public function setInputMovingAveragePeriod(int $inputMovingAveragePeriod)
    {
        $this->inputMovingAveragePeriod = $inputMovingAveragePeriod;

        return $this;
    }

    /**
     * @param array $inputArray
     *
     * @return $this
     */
    public function setInputArray(array $inputArray)
    {
        $this->inputArray  = array_values($inputArray);
        $this->outputArray = [];

        return $this;
    }

    /**
     * @param int $inputMovingAverageType
     *
     * @return $this
     */
    public function setInputMovingAverageType(int $inputMovingAverageType)
    {
        $this->inputMovingAverageType = $inputMovingAverageType;

        return $this;
    }

    /**
     * @return array
     */
    public function getOutputArray()
    {
        return $this->outputArray;
    }

    /**
     * @return $this
     * @throws \LupeCode\phpTraderNative\LupeTrader\Core\Exception
     */
    public function calculate()
    {
        $this->outputArray = [];
        if (is_null($this->inputArray) || is_null($this->inputMovingAveragePeriod)) {
            throw new Exception(Exception::INPUT_PARAMETERS_MISSING_MESSAGE, Exception::INPUT_PARAMETERS_MISSING_CODE);
        }
        $count = count($this->inputArray);
        if ($count < $this->inputMovingAveragePeriod) {
            return $this;
        }
        switch ($this->inputMovingAverageType) {
            case static::SIMPLE_MOVING_AVERAGE:
                if (empty($this->sma)) {
                    $this->sma = new SimpleMovingAverage();
                }
                $this->outputArray = $this
                    ->sma
                    ->setPeriod($this->inputMovingAveragePeriod)
                    ->setInputArray($this->inputArray)
                    ->calculate()
                    ->getOutputArray()
                ;

                return $this;
            case static::EXPONENTIAL_MOVING_AVERAGE:
            case static::WEIGHTED_MOVING_AVERAGE:
            case static::DOUBLE_EXPONENTIAL_MOVING_AVERAGE:
            case static::TRIPLE_EXPONENTIAL_MOVING_AVERAGE:
            case static::TRIANGULAR_MOVING_AVERAGE:
            case static::KAUFMAN_ADAPTIVE_MOVING_AVERAGE:
            case static::MESA_ADAPTIVE_MOVING_AVERAGE:
            case static::T3_MOVING_AVERAGE:
            default:
                return $this;
        }
    }

}

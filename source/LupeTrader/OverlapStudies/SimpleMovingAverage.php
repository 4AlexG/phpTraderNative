<?php

namespace LupeCode\phpTraderNative\LupeTrader\OverlapStudies;

use LupeCode\phpTraderNative\LupeTrader\Core\Calculation;
use LupeCode\phpTraderNative\LupeTrader\Core\Exception;

class SimpleMovingAverage implements Calculation
{

    const DEFAULT_PERIOD = 30;

    /** @var array */
    protected $inputArray;
    /** @var int */
    protected $period = self::DEFAULT_PERIOD;
    /** @var array */
    protected $outputArray;

    /**
     * @param array $inputArray
     *
     * @return $this
     */
    public function setInputArray(array $inputArray)
    {
        $this->inputArray = array_values($inputArray);

        return $this;
    }

    /**
     * @param int $period
     *
     * @return $this
     */
    public function setPeriod(int $period)
    {
        $this->period = $period;

        return $this;
    }

    /**
     * @return array
     */
    public function getOutputArray(): array
    {
        return $this->outputArray;
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function calculate()
    {
        if (empty($this->inputArray) || empty($this->period)) {
            throw new Exception(Exception::INPUT_PARAMETERS_MISSING_MESSAGE, Exception::INPUT_PARAMETERS_MISSING_CODE);
        }
        $count = count($this->inputArray);
        if ($count < $this->period) {
            $this->outputArray = [];

            return $this;
        }
        $subArray = [];
        for ($iterator = 1; $iterator < $this->period; $iterator++) {
            array_push($subArray, $this->inputArray[$iterator - 1]);
        }
        for (; $iterator <= $count; $iterator++) {
            array_push($subArray, $this->inputArray[$iterator - 1]);
            $this->outputArray[$iterator - 1] = array_sum($subArray) / $this->period;
            array_shift($subArray);
        }

        return $this;
    }

}

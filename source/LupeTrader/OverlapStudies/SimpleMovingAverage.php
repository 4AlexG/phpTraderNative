<?php

namespace LupeCode\phpTraderNative\LupeTrader\OverlapStudies;

use LupeCode\phpTraderNative\LupeTrader\Core\Exception;

class SimpleMovingAverage
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
        for ($iterator = $this->period; $iterator < $count; $iterator++) {
            $subArray = array_slice($this->inputArray, $iterator - $this->period, $this->period);
            $this->outputArray[$iterator]    = array_sum($subArray) / $this->period;
        }

        return $this;
    }

}

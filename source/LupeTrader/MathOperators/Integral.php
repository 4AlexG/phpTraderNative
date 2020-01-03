<?php

namespace LupeCode\phpTraderNative\LupeTrader\MathOperators;

use LupeCode\phpTraderNative\LupeTrader\Core\Calculation;

class Integral implements Calculation
{
    /** @var array */
    protected $inputArray;
    /** @var array */
    protected $outputArray;

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
     * @return array
     */
    public function getOutputArray(): array
    {
        return $this->outputArray;
    }

    public function calculate()
    {
        $count             = count($this->inputArray);
        $this->outputArray = [];
        for ($iterator = 1; $iterator < $count; $iterator++) {
            $this->outputArray[$iterator] = $this->inputArray[$iterator] - $this->inputArray[$iterator - 1];
        }

        return $this;
    }
}

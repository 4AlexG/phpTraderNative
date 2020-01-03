<?php

namespace LupeCode\phpTraderNative\LupeTrader\MathOperators;

use LupeCode\phpTraderNative\LupeTrader\Core\Calculation;

class Differential implements Calculation
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
        $current           = 0.;
        $this->outputArray = [];
        foreach ($this->inputArray as $iteratorValue) {
            $current             += $iteratorValue;
            $this->outputArray[] = $current;
        }

        return $this;
    }
}

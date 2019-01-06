<?php

namespace LupeCode\phpTraderNative\LupeTrader\MomentumIndicators;

class RelativeStrengthIndex
{

    const DEFAULT_PERIOD = 14;

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
     * @param int $period [OPTIONAL] [DEFAULT self::DEFAULT_PERIOD, SUGGESTED 4-200] Number of period. Valid range from 2 to 100000.
     *
     * @return $this
     */
    public function setPeriod(int $period = self::DEFAULT_PERIOD)
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
     * @param float $gains
     * @param float $losses
     *
     * @return float
     */
    protected function rsiFormula(float $gains, float $losses): float
    {
        $rs  = ($gains / $this->period) / ($losses / $this->period);
        $rsi = (100 - (100 / (1 + $rs)));

        return $rsi;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function calculate()
    {
        $this->outputArray = [];
        if (empty($this->inputArray) || empty($this->period)) {
            throw new \Exception("Input parameters missing", 7);
        }
        $count = count($this->inputArray);
        if ($count < $this->period) {
            return $this;
        }
        $gains  = 0;
        $losses = 0;
        for ($iterator = 1; $iterator <= $this->period; $iterator++) {
            $delta = $this->inputArray[$iterator] - $this->inputArray[$iterator - 1];
            if ($delta > 0) {
                $gains += $delta;
            } else {
                $losses += -$delta;
            }
        }
        $rsi = [$this->period => $this->rsiFormula($gains, $losses)];
        for (; $iterator < $count; $iterator++) {
            $delta = $this->inputArray[$iterator] - $this->inputArray[$iterator - 1];
            if ($delta > 0) {
                $gains  = $gains * ($this->period - 1) / $this->period + $delta;
                $losses = $losses * ($this->period - 1) / $this->period + 0.;
            } else {
                $gains  = $gains * ($this->period - 1) / $this->period + 0.;
                $losses = $losses * ($this->period - 1) / $this->period + -$delta;
            }
            $rsi[] = $this->rsiFormula($gains, $losses);
        }
        $this->outputArray = $rsi;

        return $this;
    }

}

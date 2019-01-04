<?php

namespace LupeCode\phpTraderNative\LupeTrader\MomentumIndicators;

use LupeCode\phpTraderNative\TALib\Enum\ReturnCode;

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
        if (empty($this->inputArray) || empty($this->period)) {
            throw new \Exception("Input parameters missing", 7);
        }
        $count = count($this->inputArray);
        if ($count < $this->period) {
            $this->outputArray = [];

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

    /**
     * Relative Strength Index
     *
     * @param array $inputArray Array of real values.
     * @param int   $period     [OPTIONAL] [DEFAULT RSI_DEFAULT_PERIOD, SUGGESTED 4-200] Number of period. Valid range from 2 to 100000.
     *
     * @return array Returns an array with calculated data.
     * @throws \Exception
     */
    public static function rsi(array $inputArray, int $period = self::DEFAULT_PERIOD): array
    {
        $self = new self();
        $self
            ->setPeriod($period)
            ->setInputArray($inputArray)
            ->calculate()
        ;

        return $self->getOutputArray();
    }

}

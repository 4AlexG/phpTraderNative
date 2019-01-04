<?php

namespace LupeCode\phpTraderNative\LupeTrader\MomentumIndicators;

use LupeCode\phpTraderNative\TALib\Enum\ReturnCode;

class RSI
{

    const DEFAULT_PERIOD = 14;

    /** @var array */
    protected $inputArray;
    /** @var int */
    protected $inputTimePeriod = self::DEFAULT_PERIOD;
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
     * @param int $inputTimePeriod
     *
     * @return $this
     */
    public function setInputTimePeriod(int $inputTimePeriod)
    {
        $this->inputTimePeriod = $inputTimePeriod;

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
        $rs  = ($gains / $this->inputTimePeriod) / ($losses / $this->inputTimePeriod);
        $rsi = (100 - (100 / (1 + $rs)));

        return $rsi;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function calculate()
    {
        if (empty($this->inputArray) || empty($this->inputTimePeriod)) {
            throw new \Exception("Input parameters missing", 7);
        }
        $count = count($this->inputArray);
        if ($count < $this->inputTimePeriod) {
            $this->outputArray = [];

            return $this;
        }
        $gains  = 0;
        $losses = 0;
        for ($iterator = 1; $iterator <= $this->inputTimePeriod; $iterator++) {
            $delta = $this->inputArray[$iterator] - $this->inputArray[$iterator - 1];
            if ($delta > 0) {
                $gains += $delta;
            } else {
                $losses += -$delta;
            }
        }
        $rsi = [$this->inputTimePeriod => $this->rsiFormula($gains, $losses)];
        for (; $iterator < $count; $iterator++) {
            $delta = $this->inputArray[$iterator] - $this->inputArray[$iterator - 1];
            if ($delta > 0) {
                $gains  = $gains * ($this->inputTimePeriod - 1) / $this->inputTimePeriod + $delta;
                $losses = $losses * ($this->inputTimePeriod - 1) / $this->inputTimePeriod + 0.;
            } else {
                $gains  = $gains * ($this->inputTimePeriod - 1) / $this->inputTimePeriod + 0.;
                $losses = $losses * ($this->inputTimePeriod - 1) / $this->inputTimePeriod + -$delta;
            }
            $rsi[] = $this->rsiFormula($gains, $losses);
        }
        $this->outputArray = $rsi;

        return $this;
    }

    /**
     * Relative Strength Index
     *
     * @param array $inputArray      Array of real values.
     * @param int   $inputTimePeriod [OPTIONAL] [DEFAULT RSI_DEFAULT_PERIOD, SUGGESTED 4-200] Number of period. Valid range from 2 to 100000.
     *
     * @return array Returns an array with calculated data.
     * @throws \Exception
     */
    public static function rsi(array $inputArray, int $inputTimePeriod = self::DEFAULT_PERIOD): array
    {
        $self = new self();
        $self
            ->setInputTimePeriod($inputTimePeriod)
            ->setInputArray($inputArray)
            ->calculate()
        ;

        return $self->getOutputArray();
    }

}

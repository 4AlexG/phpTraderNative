# Chaikin A/D Line

This indicator is a volume based indicator developed by Marc Chaikin which measures the cumulative flow of money into and out of an instrument.
The A/D line is calculated by multiplying the specific period’s volume with a multiplier that is based on the relationship of the closing price to the high-low range.
The A/D Line is formed by the running total of the Money Flow Volume. This indicator can be used to assert an underlying trend or to predict reversals.
The combination of a high positive multiplier value and high volume indicates buying pressure.
So even with a downtrend in prices when there is an uptrend in the Accumulation Distribution Line there is indication for buying pressure (accumulation) that may result to a bullish reversal.
Conversely a low negative multiplier value combined with, again, high volumes indicates selling pressure (distribution).

## PECL function
`trader_ad`

## This library's functions
`Trader::ad`  
`TraderFirendly::chaikinAccumulationDistributionLine`

## Function signature
~~~
* @param array $high   High price, array of real values.
* @param array $low    Low price, array of real values.
* @param array $close  Closing price, array of real values.
* @param array $volume Volume traded, array of real values.
*
* @return array Returns an array with calculated data.
* @throws \Exception
~~~

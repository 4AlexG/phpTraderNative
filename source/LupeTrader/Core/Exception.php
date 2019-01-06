<?php

namespace LupeCode\phpTraderNative\LupeTrader\Core;

class Exception extends \Exception
{
    /** @var int */
    const INPUT_PARAMETERS_MISSING_CODE = 1;
    /** @var string */
    const INPUT_PARAMETERS_MISSING_MESSAGE = "Input parameters missing.";
    /** @var array */
    const CODE_MESSAGES = [
        self::INPUT_PARAMETERS_MISSING_CODE => self::INPUT_PARAMETERS_MISSING_MESSAGE,
    ];
}

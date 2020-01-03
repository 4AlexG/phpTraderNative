<?php

namespace LupeCode\phpTraderNativeTest\LupeTrader\MathOperators;

use LupeCode\phpTraderNative\LupeTrader\Core\Exception;
use LupeCode\phpTraderNative\LupeTrader\MathOperators\Differential;
use LupeCode\phpTraderNative\LupeTrader\MathOperators\Integral;
use LupeCode\phpTraderNativeTest\TestingTrait;
use PHPUnit\Framework\TestCase;

// class IntegralTest extends TestCase
// {
//     use TestingTrait;
//
//     /**
//      * @throws Exception
//      */
//     public function testDifferential(): void
//     {
//         $integral = new Integral();
//         $integral->setInputArray($this->Close)->calculate();
//         $input   = $this->Close;
//         $level[] = $integral->getOutputArray();
//         for ($i = 1; $i < 10; $i++) {
//             $level[$i] = $integral->setInputArray($level[$i - 1])->calculate()->getOutputArray();
//         }
//         $table = [];
//         for ($i = 0, $iMax = count($this->Close); $i < $iMax; $i++) {
//             $row = [$this->Close[$i]];
//             foreach ($level as $le => $spot) {
//                 $row[] = $spot[$i - $le] ?? 0;
//             }
//             $table[$i] = $row;
//         }
//         var_export($table);
//     }
// }

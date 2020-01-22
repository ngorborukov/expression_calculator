<?php
namespace Shunt;

use TestCase;
use App\Services\Shunt\RPNCalculator;

class ShuntYardCalculatorTest extends TestCase
{

    /**
     * @dataProvider calculateProvider
     *
     * @param string $expression
     * @param string $expected
     */
    public function testCalculate(string $expression, string $expected)
    {
        $calculator = new RPNCalculator('RUB', new ExchangeRateProvider());

        $result = $calculator->calculate($expression);

        $this->assertEquals($expected, $result);
    }

    /**
     * @return array
     */
    public function calculateProvider(): array
    {
        return [
            [
                'expression' => '(2+2)*2',
                'expected' => '8.00 RUB'
            ],
            [
                'expression' => '2+2*2*2-2/2',
                'expected' => '9.00 RUB'
            ],
            [
                'expression' => '((100 + 20.5)*10 - 30)/2',
                'expected' => '587.50 RUB'
            ],
            [
                'expression' => '1 EUR + 1 USD',
                'expected' => '132.00 RUB'
            ],
            [
                'expression' => '((100 EUR + 20.5 USD)*10 - 30 RUB)/2',
                'expected' => '40942.50 RUB'
            ],
        ];
    }
}

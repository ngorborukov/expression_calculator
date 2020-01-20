<?php

namespace App\Services\Shunt;

use App\Services\ExchangeRateProviderInterface;
use App\Services\ExpressionCalculatorInterface;

class RPNCalculator implements ExpressionCalculatorInterface
{

    private $currency;

    /**
     * @var ExchangeRateProviderInterface
     */
    private $exchangeRateProvider;

    /**
     * @param string $currency
     * @param ExchangeRateProviderInterface $exchangeRateProvider
     */
    public function __construct(string $currency, ExchangeRateProviderInterface $exchangeRateProvider)
    {
        $this->currency = $currency;
        $this->exchangeRateProvider = $exchangeRateProvider;
    }

    /**
     * @param string $expression
     *
     * @return string
     *
     * @throws \Throwable
     */
    public function calculate(string $expression): string
    {
        $parser = new Scanner($this->currency, $this->exchangeRateProvider);

        return $this->calculateResult($parser->parse($expression));
    }

    /**
     * @param array $expressions
     *
     * @return string

     * @throws \Throwable
     */
    private function calculateResult(array $expressions): string
    {
        $operandsStack = [];
        $result = null;

        foreach ($expressions as $expression) {
            if ($expression instanceof Operation) {
                if (!$expression->enoughOperands($operandsStack)) {
                    throw new \Exception('not enough arguments');
                }
                $op2 = array_pop($operandsStack);
                $op1 = array_pop($operandsStack);
                switch ($expression->getValue()) {
                    case Operation::PLUS:
                        $result = $op1->add($op2);
                        break;
                    case Operation::MINUS:
                        $result = $op1->sub($op2);
                        break;
                    case Operation::MULTIPLY:
                        $result = $op1->multiply($op2->getValue());
                        break;
                    case Operation::DIVIDE:
                        $result = $op1->div($op2->getValue());
                        break;
                    default:
                        throw new \Exception(sprintf('Unknown operation %s', $expression->getValue()));
                }
                $operandsStack[] = $result;
                continue;
            }

            if ($expression instanceof Operand) {
                $operandsStack[] = $expression;
            }
        }

        if (!$result) {
            throw new \Exception('Unable to calculate expression');
        }

        return sprintf('%.2f %s', $result->getValue(), $this->currency);
    }
}

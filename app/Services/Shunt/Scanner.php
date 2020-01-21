<?php

namespace App\Services\Shunt;

use App\Services\ExchangeRateProviderInterface;

class Scanner
{
    private const OPERAND = 'operand';
    private const OPERATION = 'operation';
    private const PARENTHESIS = 'parenthesis';
    private const CURRENCY = 'currency';

    private const P_LEFT = '(';
    private const P_RIGHT = ')';

    private const OPERATIONS = [
        Operation::PLUS,
        Operation::MINUS,
        Operation::MULTIPLY,
        Operation::DIVIDE,
    ];

    private const PARENTHESES = [
        self::P_LEFT,
        self::P_RIGHT,
    ];

    const PATTERN = '/^([\+\-\*\/\(\)]|\d*\.\d+|\d+\.\d*|\d+|[A-Z]{3}|[ \t]+)/';

    /**
     * @var array[]
     */
    private $queue = [];

    /**
     * @var array|string[]
     */
    private $stack = [];

    /**
     * @var string
     */
    private $currency;

    /**
     * @var ExchangeRateProviderInterface
     */
    private $exchangeRateProvider;

    public function __construct(string $currency, ExchangeRateProviderInterface $exchangeRateProvider)
    {
        $this->currency = $currency;
        $this->exchangeRateProvider = $exchangeRateProvider;
    }

    /**
     * @param string $input
     *
     * @return array
     * @throws \Throwable
     */
    public function parse(string $input): array
    {
        $prevToken = null;

        while (trim($input) !== '') {
            if (!preg_match(self::PATTERN, $input, $match)) {
                throw new \Exception('Undefined symbols');
            }

            $value = $match[1];

            if (empty($value) && $value !== '0') {
                throw new \Exception('Empty data');
            }
            $input = substr($input, strlen($value));

            $value = trim($value);

            if ($value === '') {
                continue;
            }

            $this->processValue($value);
        }
        while ($tempToken = array_pop($this->stack)) {
            if ($this->getTokenType($tempToken) == self::PARENTHESIS) {
                throw new \Exception('parser error: incorrect nesting of `(` and `)`');
            }
            $this->queue[] = new Operation($tempToken);
        }

        var_dump($this->queue);
        return $this->queue;
    }

    /**
     * @param $value
     *
     * @throws \Exception
     */
    private function processValue($value): void
    {
        $tokenType = $this->getTokenType($value);
        switch ($tokenType) {
            case self::OPERAND:
                $this->queue[] = new Operand($value);

                break;
            case self::OPERATION:
                while (!empty($this->stack)) {
                    $prev = end($this->stack);
                    if ($this->getTokenType($prev) == self::OPERATION &&
                        $this->getPriority($prev) >= $this->getPriority($value)) {
                        $this->queue[] = new Operation(array_pop($this->stack));
                    } else {
                        break;
                    }
                }
                $this->stack[] = $value;

                break;
            case self::PARENTHESIS:
                if ($value == self::P_LEFT) {
                    $this->stack[] = $value;
                    break;
                }

                $isOpenParenthesis = false;
                while ($tempToken = array_pop($this->stack)) {
                    if ($tempToken == self::P_LEFT) {
                        $isOpenParenthesis = true;
                        break;
                    }
                    $this->queue[] = new Operation($tempToken);
                }

                if (!$isOpenParenthesis) {
                    throw new \Exception('No left parenthesis found');
                }

                break;
            case self::CURRENCY:
                $prevToken = array_pop($this->queue);
                if (!$prevToken instanceof Operand) {
                    throw new \Exception('Invalid currency position');
                }
                $token = $prevToken->multiply($this->exchangeRateProvider->getRate($value, $this->currency));

                $this->queue[] = $token;
                break;
            default:
                break;
        }
    }

    /**
     * @param string $value
     *
     * @return string
     * @throws \Exception
     */
    private function getTokenType(string $value): string
    {
        if (is_numeric($value)) {
            return self::OPERAND;
        }

        if (in_array($value, self::OPERATIONS)) {
            return self::OPERATION;
        }

        if (in_array($value, self::PARENTHESES)) {
            return self::PARENTHESIS;
        }

        if (strlen($value) === 3) {
            return self::CURRENCY;
        }

        throw new \Exception('Undefined token type ' . $value);
    }

    /**
     * @return int
     */
    private function getPriority($operation): int
    {
        if ($operation === Operation::PLUS || $operation === Operation::MINUS) {
            return 1;
        }

        if ($operation === Operation::DIVIDE || $operation === Operation::MULTIPLY) {
            return 10;
        }

        return -1;
    }
}

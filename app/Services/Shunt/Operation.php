<?php

namespace App\Services\Shunt;

class Operation
{
    public const PLUS = '+';
    public const MINUS = '-';
    public const MULTIPLY = '*';
    public const DIVIDE = '/';

    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        if ($this->value === self::PLUS || $this->value === self::MINUS) {
            return 1;
        }

        if ($this->value === self::DIVIDE || $this->value === self::MULTIPLY) {
            return 10;
        }

        return -1;
    }

    /**
     * @param Operation $operation
     *
     * @return bool
     */
    public function precede(Operation $operation): bool
    {
        return $this->getPriority() >= $operation->getPriority();
    }

    /**
     * @param $operands
     *
     * @return bool
     */
    public function enoughOperands($operands): bool
    {
        $numOperands = count($operands);

        switch ($this->value) {
            case self::PLUS:
            case self::MINUS:
            case self::MULTIPLY:
            case self::DIVIDE:
                return $numOperands >= 2;
            default:
                return false;
        }
    }
}

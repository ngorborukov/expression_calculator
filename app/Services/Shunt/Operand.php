<?php

namespace App\Services\Shunt;

use Throwable;

class Operand
{
    /**
     * @var float
     */
    protected $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = (float) $value;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @param Operand $operand
     *
     * @return Operand
     */
    public function add(Operand $operand): Operand
    {
        return new Operand($this->getValue() + $operand->getValue());
    }

    /**
     * @param Operand $operand
     *
     * @return Operand
     */
    public function sub(Operand $operand): Operand
    {
        return new Operand($this->getValue() - $operand->getValue());
    }

    /**
     * @param float $value
     *
     * @return Operand
     */
    public function multiply(float $value): Operand
    {
        return new Operand($this->getValue() * $value);
    }

    /**
     * @param float $value
     *
     * @return Operand
     * @throws Throwable
     */
    public function div(float $value): Operand
    {
        return new Operand($this->getValue() / $value);
    }
}

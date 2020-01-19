<?php

namespace App\Services;

interface ExpressionCalculatorInterface
{
    /**
     * @param string $expression
     *
     * @return string
     */
    public function calculate(string $expression): string;
}

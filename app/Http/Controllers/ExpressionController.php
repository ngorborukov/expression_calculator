<?php
/*
 * This file is part of ProFIT.
 *
 * Copyright (c) Opensoft (http://opensoftdev.com)
 *
 * The unauthorized use of this code outside the boundaries of Opensoft is prohibited.
 */

namespace App\Http\Controllers;

use App\Services\ExpressionCalculatorInterface;
use Illuminate\Http\Response;

class ExpressionController extends Controller
{
    /**
     * @var ExpressionCalculatorInterface
     */
    private $expressionCalculator;

    /**
     * ExpressionController constructor.
     *
     * @param ExpressionCalculatorInterface $expressionCalculator
     */
    public function __construct(ExpressionCalculatorInterface $expressionCalculator)
    {
        $this->expressionCalculator = $expressionCalculator;
    }

    /**
     * @param $expression
     *
     * @return string|Response
     */
    public function calculate($expression)
    {
        try {
            return $this->expressionCalculator->calculate(urldecode($expression));
        } catch (\Throwable $e) {
            return Response::create($e->getMessage(), 400);
        }
    }
}

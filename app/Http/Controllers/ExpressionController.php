<?php

namespace App\Http\Controllers;

use App\Services\ExpressionCalculatorInterface;
use Illuminate\Http\Request;
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
     * @param Request $request
     *
     * @return string|Response
     */
    public function calculate(Request $request)
    {
        try {
            return $this->expressionCalculator->calculate($request->input('expr'));
        } catch (\Throwable $e) {
            return Response::create($e->getMessage(), 400);
        }
    }
}

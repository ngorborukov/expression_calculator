<?php
/*
 * This file is part of ProFIT.
 *
 * Copyright (c) Opensoft (http://opensoftdev.com)
 *
 * The unauthorized use of this code outside the boundaries of Opensoft is prohibited.
 */

namespace App\Http\Controllers;

use App\Services\Shunt\ShuntYardCalculator;

class ExpressionController extends Controller
{
    /**
     * @var ShuntYardCalculator
     */
    private $shuntYardCalculator;

    public function __construct(ShuntYardCalculator $shuntYardCalculator)
    {
        $this->shuntYardCalculator = $shuntYardCalculator;
    }

    public function calculate($expression)
    {
        return $this->shuntYardCalculator->calculate($expression);
    }
}

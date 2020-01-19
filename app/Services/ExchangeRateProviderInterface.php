<?php

namespace App\Services;

interface ExchangeRateProviderInterface
{

    /**
     * @param string $from
     * @param string $to
     *
     * @return float
     */
    public function getRate($from, $to): float;
}

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
    public function getRate(string $from, string $to): float;
}

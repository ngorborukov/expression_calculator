<?php

namespace Shunt;

use App\Exceptions\ExchangeRateProviderException;
use App\Services\ExchangeRateProviderInterface;

class ExchangeRateProvider implements ExchangeRateProviderInterface
{
    public function getRate($from, $to): float
    {
        if ($from === $to) {
            return 1;
        }

        if($from == "EUR" && $to === 'RUB') {
            return 69;
        }

        if ($from == "USD" && $to === 'RUB') {
            return 63;
        }

        throw new ExchangeRateProviderException(sprintf('Unknown currency %s', $to));
    }
}

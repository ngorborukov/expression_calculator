<?php

namespace App\Providers;

use App\Services\CbrfExchangeProvider\CbrfExchangeRateProvider;
use App\Services\Shunt\RPNCalculator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\ExpressionCalculatorInterface', function () {
            $exchangeProvider = $this->app->make(CbrfExchangeRateProvider::class);

            return new RPNCalculator('RUB', $exchangeProvider);
        });
    }
}

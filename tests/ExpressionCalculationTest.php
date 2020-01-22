<?php

class ExpressionCalculationTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->get('/calculate?'. http_build_query(['expr' => '((100 EUR + 20.5 USD)*10)/2']));

        $this->assertTrue($this->response->isSuccessful());
    }
}

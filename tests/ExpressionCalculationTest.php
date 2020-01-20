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
        $this->get('/calculate/'. urlencode('((100 EUR + 20.5 USD)*10 - 30 RUB)/2'));

        $this->assertEquals(
            '/1EUR +1 USD', $this->response->getContent()
        );
    }
}

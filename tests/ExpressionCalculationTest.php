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
        $this->get('/calculate/1EUR +1 USD');

        $this->assertEquals(
            '/1EUR +1 USD', $this->response->getContent()
        );
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;

class CreateOrderTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateOrder()
    {
        $response = $this->get('/api/order/create');

        $response->assertStatus(200);
    }
}

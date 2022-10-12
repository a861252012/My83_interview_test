<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Services\OrderProcessorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class orderProcessorServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateOrder()
    {
        $fakeOrder = Order::factory()->create();
        $testServiceResult = app(OrderProcessorService::class)->process($fakeOrder->account->id);
        $testServiceResult->assertStatus(200);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CreateOrderSellTest extends TestCase
{
    use DatabaseMigrations;

    const URL = 'api/v1/trade/ordersell';

    /**
     * @test
     */
    public function guests_may_not_create_order_sell()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $orderSell = make('App\OrderSell');
        $this->post(self::URL, $orderSell->toArray());
    }

    /**
     * @test
     */
    public function an_authenticated_user_can_create_order_sale()
    {
        $this->JWTSignIn();
        $orderSell = make('App\OrderSell');
        $response = $this->post(self::URL, $orderSell->toArray());
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    function a_order_sell_requires_price()
    {
        $this->expectException('Illuminate\Validation\ValidationException');
        $this->publishOrderBuy(['price' => null]);
    }

    /**
     * @test
     */
    function a_order_sell_requires_amount()
    {
        $this->expectException('Illuminate\Validation\ValidationException');
        $this->publishOrderBuy(['amount' => null]);
    }

    /**
     * @test
     */
    function a_order_sell_requires_currency_id()
    {
        $this->expectException('Illuminate\Validation\ValidationException');
        $this->publishOrderBuy(['currency_id' => null]);
    }

    /**
     * @test
     */
    function a_order_sell_price_must_be_numeric()
    {
        $this->expectException('Illuminate\Validation\ValidationException');
        $this->publishOrderBuy(['price' => "string"]);
    }

    /**
     * @test
     */
    function a_order_sell_amount_must_be_numeric()
    {
        $this->expectException('Illuminate\Validation\ValidationException');
        $this->publishOrderBuy(['amount' => "string"]);
    }

    /**
     * @param array $overrides
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function publishOrderBuy($overrides = [])
    {
        $this->JWTSignIn();
        $orderBuy = make('App\OrderSell', $overrides);
        return $this->post(self::URL, $orderBuy->toArray());
    }
}

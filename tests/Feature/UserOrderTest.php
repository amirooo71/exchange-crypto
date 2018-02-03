<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserOrderTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function an_unauthenticated_user_can_not_get_own_order_history()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $user = create('App\User');
        $this->get("api/v1/orders/{$user->id}/history");
    }

    /**
     * @test
     */
    public function an_authenticated_user_can_get_own_order_history()
    {
        $this->JWTSignIn();
        $user = create('App\User');
        $response = $this->get("api/v1/orders/{$user->id}/history");
        $response->assertStatus(200);
    }
}

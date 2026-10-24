<?php

namespace Tests\Feature;

use Database\Seeders\RenderDemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RenderDemoTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_boots_with_sqlite_and_built_assets_and_supports_the_cart(): void
    {
        config(['demo.enabled' => true]);
        $this->seed(RenderDemoSeeder::class);
        $this->seed(RenderDemoSeeder::class);
        $this->assertDatabaseCount('products', 2);
        $this->assertDatabaseCount('users', 0);

        foreach (['/', '/1', '/vue', '/cart', '/login', '/register', '/contact'] as $path) {
            $this->get($path)->assertOk()->assertSee('動作確認用デモです。');
        }

        $this->post('/cart', ['id' => 1])->assertRedirect('/cart')->assertSessionHas('carts');
        $this->get('/cart')->assertOk()->assertSee('デモ商品：いちご');
        $this->delete('/cart/1')->assertRedirect('/cart');
    }
}

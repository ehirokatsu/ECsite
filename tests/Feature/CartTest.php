<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

//factoryで使用
use App\Models\Product;
use Illuminate\Http\Request;

class CartTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_cart_store(): void
    {
        $product = Product::factory()->create();

        $response = $this->post('/cart', [
            'id' => $product->id,
        ]);
        //\Log::info($response);
        $response->dumpSession();
        
        
        $response->assertSessionHas('cart', function ($value) {
            return $value[0]->id === 1;
        });

        $product2 = Product::factory()->create();

        $response = $this->post('/cart', [
            'id' => $product2->id,
        ]);

        $response = $this->delete('/cart/' . $product->id);
        

        $response->assertSessionHas('cart', function ($value) {
            return $value[0]->id !== 1;
        });    
        
        /*
        $response->assertSessionHas('cart', function ($value) {
            return $value[0]['product']->id === 1;
        });

        $product2 = Product::factory()->create();

        $response = $this->post('/cart', [
            'id' => $product2->id,
        ]);
        $response = $this->delete('/cart/' . $product->id);
        

        $response->assertSessionHas('cart', function ($value) {
            return $value[0]['product']->id !== 1;
        });    
        */
    }

}

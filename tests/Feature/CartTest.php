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
        \Log::info('product=' . $product);
        //$request = Request::create('/cart', 'POST', ['id' => $product->id]);
        //\Log::info('request=' . $request);


        $response = $this->post('/cart', [
            'id' => $product->id,
        ]);
        $response->dumpSession();
        //$this->assertEquals($product->id, $cart

        //$cart = $request->session()->get('cart');
        //\Log::info('cart=' . $cart);
        $this->assertEquals($product->id, $cart[0]['product']->id);
        
        

        /*
        $product = Product::factory()->create();

        // Create a new request instance
        $request = Request::create('/cart', 'POST', ['id' => $product->id]);
    
        // Call the store method
        $response = $this->app->call('App\Http\Controllers\CartController@store', [$request]);
    
        // Get the cart data from the session
        $cart = session()->get('cart');
    
        // Assert that the cart contains the expected product
        $this->assertEquals($product->id, $cart[0]['product']->id);
*/
    }
}

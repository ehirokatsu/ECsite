<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Support\Str;

//メーラブルクラス使用
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;


class ContactTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {

        Mail::fake();

        Mail::assertNothingSent();


        $response = $this->get('/contact');

        $response->assertStatus(200);

        $response = $this->post('/contact/confirm', [
            'email' => fake()->unique()->safeEmail(),
            'title' => Str::random(10),
            'body' => Str::random(10),
        ]);
        $response->assertStatus(200)->assertViewIs('contact.confirm');

        $response = $this->post('/contact/thanks', [
            'email' => fake()->unique()->safeEmail(),
            'title' => Str::random(10),
            'body' => Str::random(10),
            'action' => 'submit',
        ]);
        $response->assertStatus(200)->assertViewIs('contact.thanks');

        $response = $this->post('/contact/thanks', [
            'email' => fake()->unique()->safeEmail(),
            'title' => Str::random(10),
            'body' => Str::random(10),
            'action' => 'back',
        ]);
        $response->assertStatus(302);

        Mail::assertSent(ContactMail::class);
    }
}

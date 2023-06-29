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
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {

        //メールを実際には送信しないようにする
        Mail::fake();

        // Mailableが送信されなかったことをアサート
        Mail::assertNothingSent();

        $response = $this->get('/contact');

        $response->assertStatus(200);

        //確認フォームに遷移できること
        $response = $this->post('/contact/confirm', [
            'email' => fake()->unique()->safeEmail(),
            'title' => Str::random(10),
            'body' => Str::random(10),
        ]);
        $response->assertStatus(200)->assertViewIs('contact.confirm');

        //
        $response = $this->post('/contact/thanks', [
            'email' => fake()->unique()->safeEmail(),
            'title' => Str::random(10),
            'body' => Str::random(10),
            'action' => 'back',
        ]);
        $response->assertStatus(302);

        //メール送信されていないこと
        Mail::assertNotSent(ContactMail::class);

        $response = $this->post('/contact/thanks', [
            'email' => fake()->unique()->safeEmail(),
            'title' => Str::random(10),
            'body' => Str::random(10),
            'action' => 'submit',
        ]);
        $response->assertStatus(200)->assertViewIs('contact.thanks');

        //メール送信されていること
        Mail::assertSent(ContactMail::class);
    }

    public function test_contact_email_validate(): void
    {
        $response = $this->post('/contact/confirm', [
            'title' => Str::random(10),
            'body' => Str::random(10),
        ]);
        $response->assertRedirect('/')->assertStatus(302);
        $response->assertValid(['title', 'body']);
        $response->assertInvalid(['email']);

        $response = $this->post('/contact/confirm', [
            'email' => Str::random(10),
            'title' => Str::random(10),
            'body' => Str::random(10),
        ]);
        $response->assertRedirect('/')->assertStatus(302);
        $response->assertValid(['title', 'body']);
        $response->assertInvalid(['email']);
    }
    
    public function test_contact_title_validate(): void
    {
        $response = $this->post('/contact/confirm', [
            'email' => fake()->unique()->safeEmail(),
            'body' => Str::random(10),
        ]);
        $response->assertRedirect('/')->assertStatus(302);
        $response->assertValid(['email', 'body']);
        $response->assertInvalid(['title']);
    }

    public function test_contact_body_validate(): void
    {
        $response = $this->post('/contact/confirm', [
            'email' => fake()->unique()->safeEmail(),
            'title' => Str::random(10),
        ]);
        $response->assertRedirect('/')->assertStatus(302);
        $response->assertValid(['email', 'title']);
        $response->assertInvalid(['body']);
    }
}

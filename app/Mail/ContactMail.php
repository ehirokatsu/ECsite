<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use Illuminate\Mail\Mailables\Attachment;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    //フォーム入力された値の格納用
    private $email;
    private $title;
    private $body;
    
    /**
     * Create a new message instance.
     */
    public function __construct( $inputs )
    {
        //フォーム入力された値を格納
        $this->email = $inputs['email'];
        $this->title = $inputs['title'];
        $this->body = $inputs['body'];

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->title,
            from: 'laravel@laravel.com',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            //メール本文作成用のビューを呼び出す。値をwithで渡す。
            view: 'contact.mail',
            with: [
                'email' => $this->email,
                'title' => $this->title,
                'body' => $this->body,
            ],
            //テキストメールも送信。ビューは同じでも良い。HTMlタグがそのまま出力されるだけ
            text: 'contact.mail',

        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath('/Users/hiro/ECsite/storage/app/public/test.txt')->as('test.txt'),      
        ];
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    //名前、メール、住所、電話番号、カートの中身
    private $userInfos, $carts, $totalAmount;


    /**
     * Create a new message instance.
     */
    public function __construct($carts, $userInfos, $totalAmount)
    {
        //
        $this->userInfos = $userInfos;
        $this->carts = $carts;
        $this->totalAmount = $totalAmount;
        //dd($this->carts);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Mail',
            from: 'laravel@laravel.com',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'orderHistory.mail',
            with: [
                'userInfos' => $this->userInfos,
                'carts' => $this->carts,
                'totalAmount' => $this->totalAmount,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

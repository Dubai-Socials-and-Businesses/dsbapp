<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MarketingEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

//    public function envelope(): Envelope
//    {
//        return new Envelope(
//            subject: 'Marketing Email',
//        );
//    }

    /**
     * Get the message content definition.
     */
//    public function content(): Content
//    {
//        return new Content(
//            view: 'email.contact',
//        );
//    }

    public function build()
    {
        return $this
            ->subject($this->data['subject'])
            ->view('email.contact');
    }

    public function attachments(): array
    {
        return [];
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactoWebMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $nombre,
        public string $correo,
        public string $asunto,
        public string $mensaje
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'EDMA Web | ' . $this->asunto,
            replyTo: [
                new \Illuminate\Mail\Mailables\Address(
                    $this->correo,
                    $this->nombre
                ),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contacto-web',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
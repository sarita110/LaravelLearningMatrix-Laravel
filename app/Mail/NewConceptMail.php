<?php

namespace App\Mail;

use App\Models\Concept;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewConceptMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Concept $concept) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Concept Published: ' . $this->concept->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new-concept',
        );
    }
}

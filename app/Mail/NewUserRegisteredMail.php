<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Notifies every admin that a new user has requested an account.
 *
 * This uses Laravel's built-in mail system — no paid service required.
 * In development the MAIL_MAILER=log driver writes the email to
 * storage/logs/laravel.log so you can inspect it without a real mail server.
 *
 * To send real emails swap MAIL_MAILER in .env:
 *   - smtp  → any SMTP server (Gmail, Mailgun, Postmark, etc.)
 *   - ses   → Amazon SES (free tier available)
 *   - log   → just write to the log file (default / local dev)
 */
class NewUserRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly User $newUser) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Account Request: ' . $this->newUser->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new-user-registered',
        );
    }
}

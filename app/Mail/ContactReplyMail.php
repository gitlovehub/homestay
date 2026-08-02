<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ContactMessage $contactMessage,
        public string $replySubject,
        public string $replyMessage
    ) {
    }

    /**
     * Tiêu đề email.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->replySubject
        );
    }

    /**
     * Giao diện nội dung email.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-reply'
        );
    }

    /**
     * File đính kèm.
     */
    public function attachments(): array
    {
        return [];
    }
}
<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactReplyMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public ContactMessage $contact,
        public string $replySubject,
        public string $replyMessage
    ) {
    }

    public function build(): static
    {
        return $this
            ->subject($this->replySubject)
            ->view('emails.contact-reply');
    }
}
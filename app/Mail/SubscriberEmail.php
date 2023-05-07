<?php

namespace App\Mail;

use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscriberEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $record, $subject, $body, $subscriber;

    /**
     * Create a new message instance.
     */
    public function __construct($record, $subscriber)
    {
        $this->record = $record;
        $this->subject = $record->subject;
        $this->body = $record->body;
        $this->subscriber = $subscriber;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.subscribers.email',
            // with: [
            //     'subscriber' => $this->subscriber,
            // ],
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

    /**
     * Get the message headers.
     */
    public function headers(): Headers
    {
        return new Headers(
            text: [
                'subscriber_id' => $this->subscriber->id,
                'template_id' => $this->record->id,
            ],
        );
    }
}

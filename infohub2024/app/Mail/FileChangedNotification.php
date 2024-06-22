<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FileChangedNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $messageContent;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $messageContent)
    {
        $this->user = $user;
        $this->messageContent = $messageContent;
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[MKT -- Powiadomienie] | Nowe zmiany w strukturze plików',
        );
    }

    /**
     * Get the message content definition.
     */
    public function build()
    {
        return $this->markdown('emails.file_changed')
                    ->subject('File Change Notification')
                    ->with([
                        'messageContent' => $this->messageContent,
                    ]);
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

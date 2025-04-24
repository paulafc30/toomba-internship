<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\TemporaryLink; // Asegúrate de importar el modelo TemporaryLink

class UploadLinkEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $uploadLink;
    public $recipientName;
    public $temporaryLink;

    /**
     * Create a new message instance.
     *
     * @param  string  $uploadLink
     * @param  string  $recipientName
     * @param  TemporaryLink  $temporaryLink 
     * @return void
     */
    public function __construct(string $uploadLink, string $recipientName, TemporaryLink $temporaryLink)
    {
        $this->uploadLink = $uploadLink;
        $this->recipientName = $recipientName;
        $this->temporaryLink = $temporaryLink; 
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your File Upload Link',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'admin.upload-link',
            with: [
                'uploadLink' => $this->uploadLink,
                'recipientName' => $this->recipientName,
                'temporaryLink' => $this->temporaryLink,
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
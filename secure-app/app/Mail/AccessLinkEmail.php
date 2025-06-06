<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\TemporaryLink;

class AccessLinkEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $accessLink;
    public $name;
    public $temporaryLink;
    public $password;
    public $expirationDate; 

    /**
     * Create a new message instance.
     *
     * @param  string  $accessLink
     * @param  string  $name
     * @param  \App\Models\TemporaryLink  $temporaryLink
     * @param  string|null  $password
     * @return void
     */
    public function __construct(string $accessLink, string $name, TemporaryLink $temporaryLink, ?string $password = null)
    {
        $this->accessLink = $accessLink;
        $this->name = $name;
        $this->temporaryLink = $temporaryLink;
        $this->password = $password;
        $this->expirationDate = $temporaryLink->expires_at instanceof \DateTimeInterface
            ? $temporaryLink->expires_at->format('Y-m-d H:i')
            : (string) $temporaryLink->expires_at; // Manejo por si acaso no es un objeto DateTime
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Temporary Link',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'admin.access-link',
            with: [
                'accessLink' => $this->accessLink,
                'name' => $this->name,
                'password' => $this->password,
                'expirationDate' => $this->expirationDate, // Pasar la fecha formateada
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
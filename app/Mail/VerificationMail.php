<?php

namespace App\Mail;

use App\Models\Buyer;
use App\Models\Seller;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(protected User $user)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('admin@allofyou.id', 'Aura'),
            to: [$this->user->email],
            subject: 'Verifikasi',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.verification',
            with: [
                'name'      => $this->user->name,
                'link'      => $this->user->link_verification,
                'app_name'  => config('app.name'),
                'is_seller' => $this->user instanceof Seller,
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
        if ($this->user instanceof Buyer) return [];

        return [
            Attachment::fromPath(public_path('pdf/verification.pdf'))
                ->as('verifikasi.pdf')
                ->withMime('application/pdf'),
        ];
    }
}

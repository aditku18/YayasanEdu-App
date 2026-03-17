<?php

namespace App\Mail;

use App\Models\Foundation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrialExpired extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Foundation $foundation
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔒 Masa Trial Anda Telah Berakhir',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.trial-expired',
            with: [
                'foundation' => $this->foundation,
                'subdomain' => $this->foundation->subdomain,
            ]
        );
    }
}

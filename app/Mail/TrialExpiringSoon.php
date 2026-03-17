<?php

namespace App\Mail;

use App\Models\Foundation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrialExpiringSoon extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Foundation $foundation,
        public int $daysLeft
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "⚠️ Masa Trial Anda Akan Berakhir dalam {$this->daysLeft} Hari",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.trial-expiring-soon',
            with: [
                'foundation' => $this->foundation,
                'daysLeft' => $this->daysLeft,
                'trialEndsAt' => $this->foundation->trial_ends_at->format('d M Y'),
                'subdomain' => $this->foundation->subdomain,
            ]
        );
    }
}

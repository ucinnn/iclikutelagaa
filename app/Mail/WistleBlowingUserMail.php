<?php
namespace App\Mail;

use App\Models\WistleBlowing;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WistleBlowingUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public WistleBlowing $report) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Laporan Whistle Blowing Anda Telah Diterima',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.wistle-blowing.user',
        );
    }
}
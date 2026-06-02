<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HelpdeskTicketReplied extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public $ticket,
        public $reply
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ada Balasan Baru - ' . $this->ticket->subject,
        );
    }

    public function content(): Content
    {
        return new Content(htmlString: $this->buildHtml());
    }

    private function buildHtml(): string
    {
        $appName   = config('app.name');
        $appUrl    = config('app.url');
        $subject   = e($this->ticket->subject);
        $message   = nl2br(e($this->reply->message));
        $repliedAt = $this->reply->created_at->format('d M Y, H:i');
        $userName  = e($this->ticket->user->name ?? 'Pengguna');

        return <<<HTML
        <!DOCTYPE html>
        <html lang="id">
        <head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
        <body style="font-family:Arial,sans-serif;background:#f4f6f9;margin:0;padding:0;">
          <div style="max-width:600px;margin:30px auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 15px rgba(0,0,0,.08);">
            <div style="background:linear-gradient(135deg,#2563eb,#4f46e5);padding:36px 32px;text-align:center;">
              <div style="font-size:48px;margin-bottom:12px;">💬</div>
              <h1 style="color:#fff;margin:0;font-size:22px;font-weight:700;">Ada Balasan Baru!</h1>
              <p style="color:#bfdbfe;margin:8px 0 0;font-size:14px;">Tim Pusat Bantuan telah membalas pesan Anda</p>
            </div>
            <div style="padding:32px;">
              <p style="color:#374151;font-size:15px;line-height:1.6;">Halo <strong>{$userName}</strong>,</p>
              <p style="color:#374151;font-size:15px;line-height:1.6;">Tim Pusat Bantuan telah membalas pesan Anda. Berikut balasan yang dikirimkan:</p>
              <div style="background:#f0f4ff;border-radius:8px;padding:12px 18px;margin-bottom:20px;font-size:14px;color:#374151;">
                📌 Topik: <strong style="color:#2563eb;">{$subject}</strong>
              </div>
              <div style="background:#2563eb;border-radius:12px 12px 12px 4px;padding:20px 24px;margin:20px 0;">
                <div style="font-size:12px;color:#93c5fd;margin-bottom:8px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">💬 Tim Pusat Bantuan</div>
                <div style="font-size:15px;color:#fff;line-height:1.6;">{$message}</div>
                <div style="font-size:12px;color:#93c5fd;margin-top:10px;">{$repliedAt} WIB</div>
              </div>
              <p style="text-align:center;">
                <a href="{$appUrl}/helpdesk" style="display:inline-block;background:#2563eb;color:#fff;text-decoration:none;padding:12px 28px;border-radius:8px;font-weight:600;font-size:14px;">🔗 Lihat &amp; Balas di Helpdesk</a>
              </p>
              <p style="color:#6b7280;font-size:13px;">Jika Anda ingin membalas, klik tombol di atas untuk membuka halaman helpdesk.</p>
            </div>
            <div style="background:#f9fafb;border-top:1px solid #e5e7eb;padding:20px 32px;text-align:center;">
              <p style="color:#9ca3af;font-size:12px;margin:0;">Email ini dikirim otomatis oleh <a href="{$appUrl}" style="color:#2563eb;text-decoration:none;">{$appName}</a>. Mohon jangan membalas email ini.</p>
            </div>
          </div>
        </body>
        </html>
        HTML;
    }
}
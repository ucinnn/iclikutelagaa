<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HelpdeskTicketCreated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public $ticket,
        public string $recipientType = 'user' // 'user' | 'department'
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->recipientType === 'user'
            ? 'Pesan Bantuan Berhasil Dikirim - ' . $this->ticket->subject
            : '[Helpdesk] Pesan Baru Masuk - ' . $this->ticket->subject;

        return new Envelope(subject: $subject);
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
        $message   = nl2br(e($this->ticket->message));
        $createdAt = $this->ticket->created_at->format('d M Y, H:i');
        $userName  = e($this->ticket->user->name ?? 'Pengguna');
        $userEmail = e($this->ticket->user->email ?? '-');

        if ($this->recipientType === 'user') {
            return <<<HTML
            <!DOCTYPE html>
            <html lang="id">
            <head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
            <body style="font-family:Arial,sans-serif;background:#f4f6f9;margin:0;padding:0;">
              <div style="max-width:600px;margin:30px auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 15px rgba(0,0,0,.08);">
                <div style="background:linear-gradient(135deg,#2563eb,#4f46e5);padding:36px 32px;text-align:center;">
                  <div style="font-size:48px;margin-bottom:12px;">✅</div>
                  <h1 style="color:#fff;margin:0;font-size:22px;font-weight:700;">Pesan Bantuan Terkirim!</h1>
                  <p style="color:#bfdbfe;margin:8px 0 0;font-size:14px;">Tim kami akan segera merespons pesan Anda</p>
                </div>
                <div style="padding:32px;">
                  <p style="color:#374151;font-size:15px;line-height:1.6;">Halo <strong>{$userName}</strong>,</p>
                  <p style="color:#374151;font-size:15px;line-height:1.6;">Pesan bantuan Anda telah berhasil kami terima. Berikut ringkasannya:</p>
                  <div style="background:#f0f4ff;border-left:4px solid #2563eb;border-radius:8px;padding:20px 24px;margin:20px 0;">
                    <div style="font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;">Topik</div>
                    <div style="font-size:15px;color:#111827;font-weight:600;margin-bottom:12px;">{$subject}</div>
                    <div style="font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;">Pesan Anda</div>
                    <div style="font-size:14px;color:#374151;background:#fff;border-radius:6px;padding:12px 16px;border:1px solid #e5e7eb;">{$message}</div>
                    <div style="font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin-top:12px;margin-bottom:4px;">Waktu Pengiriman</div>
                    <div style="font-size:14px;color:#111827;">{$createdAt} WIB</div>
                  </div>
                  <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:16px 20px;margin:20px 0;">
                    <p style="color:#92400e;font-size:13px;margin:0;">⏱️ <strong>Waktu respons rata-rata:</strong> 2–4 jam pada hari kerja (Senin–Jumat, 08.00–17.00 WIB).</p>
                  </div>
                  <p style="color:#374151;font-size:15px;">Terima kasih telah menghubungi kami!</p>
                </div>
                <div style="background:#f9fafb;border-top:1px solid #e5e7eb;padding:20px 32px;text-align:center;">
                  <p style="color:#9ca3af;font-size:12px;margin:0;">Email ini dikirim otomatis oleh <a href="{$appUrl}" style="color:#2563eb;text-decoration:none;">{$appName}</a>. Mohon jangan membalas email ini.</p>
                </div>
              </div>
            </body>
            </html>
            HTML;
        }

        // recipientType === 'department'
        return <<<HTML
        <!DOCTYPE html>
        <html lang="id">
        <head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
        <body style="font-family:Arial,sans-serif;background:#f4f6f9;margin:0;padding:0;">
          <div style="max-width:600px;margin:30px auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 15px rgba(0,0,0,.08);">
            <div style="background:linear-gradient(135deg,#dc2626,#b91c1c);padding:36px 32px;text-align:center;">
              <div style="font-size:48px;margin-bottom:12px;">🔔</div>
              <h1 style="color:#fff;margin:0;font-size:22px;font-weight:700;">Pesan Baru Masuk!</h1>
              <p style="color:#fecaca;margin:8px 0 0;font-size:14px;">Departemen {$subject} menerima pesan baru</p>
            </div>
            <div style="padding:32px;">
              <p style="color:#374151;font-size:15px;line-height:1.6;">Halo Tim <strong>{$subject}</strong>,</p>
              <p style="color:#374151;font-size:15px;line-height:1.6;">Ada pesan bantuan baru yang masuk dan membutuhkan perhatian Anda segera.</p>
              <div style="background:#fff5f5;border-left:4px solid #dc2626;border-radius:8px;padding:20px 24px;margin:20px 0;">
                <div style="font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;">Dari Pengguna</div>
                <div style="font-size:15px;color:#111827;font-weight:600;margin-bottom:12px;">{$userName}</div>
                <div style="font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;">Email Pengguna</div>
                <div style="font-size:14px;color:#111827;margin-bottom:12px;">{$userEmail}</div>
                <div style="font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;">Topik / Departemen</div>
                <div style="font-size:15px;color:#111827;font-weight:600;margin-bottom:12px;">{$subject}</div>
                <div style="font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;">Pesan</div>
                <div style="font-size:14px;color:#374151;background:#fff;border-radius:6px;padding:12px 16px;border:1px solid #e5e7eb;">{$message}</div>
                <div style="font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin-top:12px;margin-bottom:4px;">Waktu Masuk</div>
                <div style="font-size:14px;color:#111827;">{$createdAt} WIB</div>
              </div>
              <p style="text-align:center;">
                <a href="{$appUrl}/admin/helpdesk-messages" style="display:inline-block;background:#2563eb;color:#fff;text-decoration:none;padding:12px 28px;border-radius:8px;font-weight:600;font-size:14px;">🔗 Lihat &amp; Balas di Dashboard</a>
              </p>
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
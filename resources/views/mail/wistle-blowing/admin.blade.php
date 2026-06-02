<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #dc2626, #e11d48); padding: 30px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 22px; }
        .header p { color: #fecaca; margin: 8px 0 0; font-size: 14px; }
        .body { padding: 30px; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; background: #fee2e2; color: #dc2626; }
        .field { margin-bottom: 20px; }
        .field label { display: block; font-size: 12px; color: #6b7280; font-weight: bold; text-transform: uppercase; margin-bottom: 4px; }
        .field p { margin: 0; font-size: 14px; color: #1f2937; background: #f9fafb; padding: 10px 14px; border-radius: 6px; border-left: 3px solid #dc2626; }
        .links a { display: block; color: #2563eb; font-size: 13px; margin-bottom: 4px; word-break: break-all; }
        .footer { background: #f9fafb; padding: 20px 30px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb; }
        .btn { display: inline-block; background: #dc2626; color: #fff !important; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: bold; margin-top: 16px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🚨 Laporan Whistle Blowing Baru</h1>
        <p>Laporan baru telah masuk dan perlu ditindaklanjuti</p>
    </div>
    <div class="body">
        <p style="color:#6b7280;font-size:14px;margin-top:0;">Diterima pada <strong>{{ $report->created_at->format('d M Y, H:i') }} WIB</strong></p>

        <div class="field">
            <label>Kategori</label>
            <p><span class="badge">{{ $report->category }}</span></p>
        </div>
        <div class="field">
            <label>Nama Pelaku / Subjek</label>
            <p>{{ $report->subject }}</p>
        </div>
        <div class="field">
            <label>Divisi Terkait</label>
            <p>{{ $report->division }}</p>
        </div>
        <div class="field">
            <label>Kronologi Kejadian</label>
            <p style="white-space:pre-line;">{{ $report->description }}</p>
        </div>

        @if(!empty($report->proof))
            <div class="field">
                <label>Bukti File ({{ count($report->proof) }} file)</label>
                <p>File bukti tersedia di sistem. Login untuk mengunduh.</p>
            </div>
        @endif

        @if(!empty($report->links))
            <div class="field">
                <label>Link Bukti</label>
                <div class="links" style="background:#f9fafb;padding:10px 14px;border-radius:6px;border-left:3px solid #dc2626;">
                    @foreach($report->links as $link)
                        <a href="{{ $link }}">{{ $link }}</a>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="field">
            <label>Pelapor</label>
            <p>{{ $report->user->name ?? 'Anonim' }} ({{ $report->user->email ?? '-' }})</p>
        </div>

        <a href="{{ config('app.url') }}/admin" class="btn">Lihat di Dashboard Admin</a>
    </div>
    <div class="footer">
        <p>Email ini dikirim otomatis oleh sistem {{ config('app.name') }}</p>
        <p>{{ config('app.url') }}</p>
    </div>
</div>
</body>
</html>
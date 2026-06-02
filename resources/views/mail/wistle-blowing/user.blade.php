<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #1d4ed8, #4f46e5); padding: 30px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 22px; }
        .header p { color: #bfdbfe; margin: 8px 0 0; font-size: 14px; }
        .body { padding: 30px; }
        .info-box { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 16px 20px; margin-bottom: 20px; }
        .info-box p { margin: 0; font-size: 14px; color: #1e40af; }
        .field { margin-bottom: 16px; }
        .field label { display: block; font-size: 12px; color: #6b7280; font-weight: bold; text-transform: uppercase; margin-bottom: 4px; }
        .field p { margin: 0; font-size: 14px; color: #1f2937; background: #f9fafb; padding: 10px 14px; border-radius: 6px; }
        .notice { background: #fefce8; border: 1px solid #fde68a; border-radius: 8px; padding: 16px 20px; margin-top: 20px; }
        .notice p { margin: 0; font-size: 13px; color: #92400e; }
        .footer { background: #f9fafb; padding: 20px 30px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>✅ Laporan Anda Telah Diterima</h1>
        <p>Terima kasih atas keberanian Anda melapor</p>
    </div>
    <div class="body">
        <p style="font-size:15px;color:#374151;">Halo <strong>{{ $report->user->name ?? 'Pelapor' }}</strong>,</p>
        <p style="font-size:14px;color:#6b7280;">Laporan whistle blowing Anda telah berhasil kami terima pada <strong>{{ $report->created_at->format('d M Y, H:i') }} WIB</strong>. Tim kami akan segera menindaklanjuti laporan ini.</p>

        <div class="info-box">
            <p>🔒 Identitas dan informasi Anda dijaga kerahasiaannya sesuai kebijakan perusahaan.</p>
        </div>

        <p style="font-size:13px;font-weight:bold;color:#374151;margin-bottom:12px;">Ringkasan Laporan:</p>

        <div class="field">
            <label>Kategori</label>
            <p>{{ $report->category }}</p>
        </div>
        <div class="field">
            <label>Divisi Terkait</label>
            <p>{{ $report->division }}</p>
        </div>
        <div class="field">
            <label>Tanggal Laporan</label>
            <p>{{ $report->created_at->format('d M Y, H:i') }} WIB</p>
        </div>
        <div class="field">
            <label>Status</label>
            <p>⏳ Pending — Sedang diproses</p>
        </div>

        <div class="notice">
            <p>⏱ Estimasi respons: <strong>1-3 hari kerja</strong>. Anda dapat memantau status laporan dengan login ke akun Anda.</p>
        </div>
    </div>
    <div class="footer">
        <p>Email ini dikirim otomatis oleh sistem {{ config('app.name') }}</p>
        <p>Jangan balas email ini. Hubungi kami di <a href="mailto:{{ env('APP_SITEMAIL') }}">{{ env('APP_SITEMAIL') }}</a></p>
    </div>
</div>
</body>
</html>
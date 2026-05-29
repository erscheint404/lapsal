<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Dikonfirmasi</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .card { background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
        .header { background: linear-gradient(135deg, #10b981, #059669); padding: 32px 28px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 22px; font-weight: 800; }
        .header p { color: #d1fae5; margin: 6px 0 0; font-size: 14px; }
        .body { padding: 32px 28px; }
        .badge { display: inline-block; background: #d1fae5; color: #065f46; padding: 6px 16px; border-radius: 50px; font-size: 13px; font-weight: 700; }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: #6b7280; }
        .detail-value { font-weight: 700; color: #111827; }
        .qr-section { text-align: center; padding: 16px 0; }
        .qr-section img { width: 180px; height: 180px; }
        .cta { text-align: center; padding: 8px 0 24px; }
        .cta a { display: inline-block; background: #10b981; color: #fff; padding: 14px 36px; border-radius: 12px; text-decoration: none; font-weight: 700; font-size: 15px; }
        .footer { text-align: center; padding: 20px 28px; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb; }
        .logo { width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 8px; font-size: 24px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <div class="logo">⚽</div>
                <h1>{{ config('app.name') }}</h1>
                <p>Booking Anda telah dikonfirmasi</p>
            </div>
            <div class="body">
                <div style="text-align:center;margin-bottom:20px;">
                    <span class="badge">Dikonfirmasi</span>
                    <h2 style="margin:12px 0 0;font-size:18px;color:#111827;">{{ $booking->kode_booking }}</h2>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Lapangan</span>
                    <span class="detail-value">{{ $booking->lapangan->nama }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tanggal</span>
                    <span class="detail-value">{{ $booking->tanggal->translatedFormat('l, d F Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Waktu</span>
                    <span class="detail-value">{{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }} WIB</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Durasi</span>
                    <span class="detail-value">{{ $booking->durasi_jam }} Jam</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Total Bayar</span>
                    <span class="detail-value" style="color:#059669;">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                </div>

                @if($booking->qr_code_path)
                <div class="qr-section">
                    <p style="font-size:13px;color:#6b7280;margin-bottom:10px;">Tunjukkan QR Code ini saat tiba di lokasi:</p>
                    <img src="{{ asset('storage/' . $booking->qr_code_path) }}" alt="QR Code Booking">
                </div>
                @endif

                <div class="cta">
                    <a href="{{ route('member.booking.show', $booking->id) }}">Lihat Detail Booking</a>
                </div>

                <p style="font-size:13px;color:#6b7280;text-align:center;margin:0;">
                    Silakan datang 15 menit sebelum jadwal bermain.
                </p>
            </div>
            <div class="footer">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                <p>Email ini dikirim otomatis, harap tidak membalas.</p>
            </div>
        </div>
    </div>
</body>
</html>

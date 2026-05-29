<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Status Booking</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .card { background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
        .header { padding: 32px 28px; text-align: center; }
        .header.rejected { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .header.cancelled { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .header.completed { background: linear-gradient(135deg, #3b82f6, #2563eb); }
        .header.other { background: linear-gradient(135deg, #6b7280, #4b5563); }
        .header h1 { color: #fff; margin: 0; font-size: 22px; font-weight: 800; }
        .header p { color: rgba(255,255,255,0.85); margin: 6px 0 0; font-size: 14px; }
        .body { padding: 32px 28px; }
        .badge { display: inline-block; padding: 6px 16px; border-radius: 50px; font-size: 13px; font-weight: 700; }
        .badge.success { background: #d1fae5; color: #065f46; }
        .badge.danger { background: #fee2e2; color: #991b1b; }
        .badge.warning { background: #fef3c7; color: #92400e; }
        .badge.info { background: #dbeafe; color: #1e40af; }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: #6b7280; }
        .detail-value { font-weight: 700; color: #111827; }
        .message-box { background: #f9fafb; border-radius: 12px; padding: 16px; margin: 16px 0; border-left: 4px solid #e5e7eb; font-size: 14px; color: #374151; }
        .cta { text-align: center; padding: 8px 0 24px; }
        .cta a { display: inline-block; background: #111827; color: #fff; padding: 14px 36px; border-radius: 12px; text-decoration: none; font-weight: 700; font-size: 15px; }
        .footer { text-align: center; padding: 20px 28px; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb; }
        .logo { width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 8px; font-size: 24px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            @php
                $headerClass = match($booking->status) {
                    'rejected' => 'rejected',
                    'cancelled' => 'cancelled',
                    'completed' => 'completed',
                    default => 'other',
                };
                $badgeClass = match($booking->status) {
                    'confirmed', 'completed' => 'success',
                    'rejected', 'cancelled' => 'danger',
                    'pending_payment', 'waiting_confirmation' => 'warning',
                    default => 'info',
                };
                $statusIcon = match($booking->status) {
                    'confirmed' => '✅',
                    'completed' => '✅',
                    'rejected' => '❌',
                    'cancelled' => 'ℹ️',
                    default => 'ℹ️',
                };
                $statusTitle = match($booking->status) {
                    'rejected' => 'Booking Ditolak',
                    'cancelled' => 'Booking Dibatalkan',
                    'completed' => 'Booking Selesai',
                    'confirmed' => 'Booking Dikonfirmasi',
                    default => 'Update Status Booking',
                };
            @endphp
            <div class="header {{ $headerClass }}">
                <div class="logo">{{ $statusIcon }}</div>
                <h1>{{ config('app.name') }}</h1>
                <p>{{ $statusTitle }}</p>
            </div>
            <div class="body">
                <div style="text-align:center;margin-bottom:20px;">
                    <span class="badge {{ $badgeClass }}">{{ $booking->status_label }}</span>
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
                    <span class="detail-label">Total Bayar</span>
                    <span class="detail-value">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                </div>

                @if($booking->status === 'rejected' && $booking->alasan_penolakan)
                <div class="message-box" style="border-left-color: #ef4444;">
                    <strong>Alasan Penolakan:</strong><br>{{ $booking->alasan_penolakan }}
                </div>
                @endif

                @if($booking->status === 'cancelled')
                <div class="message-box" style="border-left-color: #f59e0b;">
                    Booking ini telah dibatalkan. Jika ada refund, akan diproses sesuai ketentuan yang berlaku.
                </div>
                @endif

                @if($booking->status === 'completed')
                <div class="message-box" style="border-left-color: #3b82f6;">
                    Terima kasih telah bermain! Silakan beri rating untuk lapangan yang Anda gunakan.
                </div>
                @endif

                <div class="cta">
                    <a href="{{ route('member.booking.show', $booking->id) }}">Lihat Detail Booking</a>
                </div>
            </div>
            <div class="footer">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                <p>Email ini dikirim otomatis, harap tidak membalas.</p>
            </div>
        </div>
    </div>
</body>
</html>

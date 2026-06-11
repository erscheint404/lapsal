<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pendapatan</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 18px; text-align: center; margin-bottom: 5px; }
        .subtitle { text-align: center; font-size: 11px; color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
        th { background-color: #059669; color: white; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .summary-box { background: #f3f4f6; padding: 15px; border-radius: 4px; margin-bottom: 20px; text-align: center; }
        .summary-box .total { font-size: 20px; font-weight: bold; color: #059669; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { text-align: center; font-size: 10px; color: #999; margin-top: 30px; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <h1>Laporan Pendapatan Lapangan Futsal</h1>
    <p class="subtitle">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} — {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>

    <div class="summary-box">
        <p style="margin: 0 0 5px 0; font-size: 11px; color: #666;">Total Pendapatan</p>
        <p class="total" style="margin: 0;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
    </div>

    <h3 style="font-size: 13px;">Pendapatan Harian</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th class="text-center">Jumlah Booking</th>
                <th class="text-right">Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($perHari as $data)
            <tr>
                <td>{{ \Carbon\Carbon::parse($data['tanggal'])->format('d M Y') }}</td>
                <td class="text-center">{{ $data['jumlah_booking'] }}</td>
                <td class="text-right">Rp {{ number_format($data['pendapatan'], 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">Tidak ada data.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <h3 style="font-size: 13px;">Detail Transaksi</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Booking</th>
                <th>Member</th>
                <th>Lapangan</th>
                <th>Tanggal</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $index => $booking)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $booking->kode_booking }}</td>
                <td>{{ $booking->user->name ?? '-' }}</td>
                <td>{{ $booking->lapangan->nama ?? '-' }}</td>
                <td>{{ $booking->tanggal->format('d M Y') }}</td>
                <td class="text-right">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        &copy; {{ date('Y') }} Lapsal - Dicetak pada {{ now()->format('d M Y H:i') }}
    </div>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penyewaan</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 18px; text-align: center; margin-bottom: 5px; }
        .subtitle { text-align: center; font-size: 11px; color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
        th { background-color: #059669; color: white; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .summary-box { background: #f3f4f6; padding: 12px; border-radius: 4px; margin-bottom: 20px; }
        .summary-box span { display: inline-block; margin-right: 30px; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { text-align: center; font-size: 10px; color: #999; margin-top: 30px; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <h1>Laporan Penyewaan Lapangan Futsal</h1>
    <p class="subtitle">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} — {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>

    <div class="summary-box">
        <span>Total Booking: {{ $summary['total'] }}</span>
        <span>Confirmed: {{ $summary['confirmed'] }}</span>
        <span>Completed: {{ $summary['completed'] }}</span>
        <span>Cancelled: {{ $summary['cancelled'] }}</span>
    </div>

    @if($perLapangan->isNotEmpty())
    <h3 style="font-size: 13px;">Ringkasan Per Lapangan</h3>
    <table>
        <thead>
            <tr>
                <th>Lapangan</th>
                <th class="text-center">Total Booking</th>
                <th class="text-right">Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($perLapangan as $data)
            <tr>
                <td>{{ $data['nama'] }}</td>
                <td class="text-center">{{ $data['total'] }}</td>
                <td class="text-right">Rp {{ number_format($data['pendapatan'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <h3 style="font-size: 13px;">Detail Penyewaan</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Booking</th>
                <th>Member</th>
                <th>Lapangan</th>
                <th>Tanggal</th>
                <th>Status</th>
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
                <td>{{ $booking->status_label }}</td>
                <td class="text-right">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        &copy; {{ date('Y') }} Lapsal - Dicetak pada {{ now()->format('d M Y H:i') }}
    </div>
</body>
</html>

@extends('layouts.app')
@section('title', 'Riwayat Booking')
@section('content')
<div class="bg-dark-50 py-12 min-h-screen">
    <div class="container-custom max-w-5xl">
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark-900">Riwayat Booking</h1>
                <p class="text-dark-500 mt-1">Daftar semua penyewaan lapangan Anda.</p>
            </div>
            
            <form action="{{ route('member.booking.index') }}" method="GET" class="flex gap-2">
                <select name="status" class="form-input" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending_payment" {{ request('status') == 'pending_payment' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Review Pembayaran</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Terkonfirmasi</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </form>
        </div>

        <div class="card overflow-hidden">
            @if($bookings->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-dark-50 border-b border-dark-100">
                            <th class="py-4 px-6 text-xs font-bold text-dark-500 uppercase tracking-wider">Booking Info</th>
                            <th class="py-4 px-6 text-xs font-bold text-dark-500 uppercase tracking-wider">Jadwal</th>
                            <th class="py-4 px-6 text-xs font-bold text-dark-500 uppercase tracking-wider">Total Harga</th>
                            <th class="py-4 px-6 text-xs font-bold text-dark-500 uppercase tracking-wider">Status</th>
                            <th class="py-4 px-6 text-xs font-bold text-dark-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dark-100">
                        @foreach($bookings as $booking)
                        <tr class="hover:bg-dark-50/50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="font-bold text-dark-900 mb-1">{{ $booking->lapangan->nama }}</div>
                                <div class="text-xs text-dark-500 font-mono">{{ $booking->kode_booking }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-sm font-medium text-dark-800 mb-1">{{ $booking->tanggal->format('d M Y') }}</div>
                                <div class="text-xs text-dark-500">{{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }} ({{ $booking->durasi_jam }} Jam)</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-sm font-bold text-primary-600">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="badge {{ $booking->status_color }} whitespace-nowrap">{{ $booking->status_label }}</span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                @if(in_array($booking->status, ['pending_payment', 'draft']))
                                <a href="{{ route('member.booking.checkout', $booking->id) }}" class="btn-primary btn-sm whitespace-nowrap">
                                    Bayar
                                </a>
                                @else
                                <a href="{{ route('member.booking.show', $booking->id) }}" class="btn-secondary btn-sm whitespace-nowrap">
                                    Detail
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-t border-dark-100">
                {{ $bookings->links() }}
            </div>
            
            @else
            <div class="p-12 text-center text-dark-500">
                <div class="w-16 h-16 mx-auto bg-dark-50 rounded-full flex items-center justify-center text-2xl text-dark-300 mb-4">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3 class="font-bold text-lg text-dark-800 mb-1">Belum Ada Data</h3>
                <p class="text-sm">Anda belum memiliki riwayat booking lapangan.</p>
                <a href="{{ route('lapangan.index') }}" class="btn-primary mt-6 inline-flex">Cari Lapangan</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

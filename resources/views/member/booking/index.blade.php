@extends('layouts.app')
@section('title', 'Riwayat Booking')
@section('content')
<div class="section-gradient min-h-screen pt-28 pb-16">
    <div class="container-custom max-w-5xl">

        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4 reveal">
            <div>
                <span class="text-sm font-bold uppercase tracking-widest mb-2 block" style="color: #6e8f00;">Aktivitas Member</span>
                <h1 class="text-3xl lg:text-4xl font-display font-black text-dark-900 tracking-tight">Riwayat Booking</h1>
                <p class="text-dark-500 mt-2 leading-relaxed">Kelola dan pantau semua transaksi penyewaan lapanganmu.</p>
            </div>
            <a href="{{ route('lapangan.index') }}" class="btn-primary shadow-xl">
                <i class="fas fa-plus mr-2"></i>Booking Baru
            </a>
        </div>

        <div class="card-premium overflow-hidden reveal-scale" style="transition-delay: 100ms;">
            <div class="overflow-x-auto">
                <table class="table-modern w-full">
                    <thead>
                        <tr>
                            <th class="py-4 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider">Lapangan & Waktu</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider">Kode Booking</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider">Total Harga</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider">Status</th>
                            <th class="py-4 px-6 text-right text-xs font-bold text-dark-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dark-100/40">
                        @forelse($bookings as $booking)
                        <tr class="hover:bg-dark-50/50 transition-colors group">
                            <td class="py-4 px-6">
                                <div class="font-bold text-dark-900 text-sm mb-1 line-clamp-1">{{ $booking->lapangan->nama }}</div>
                                <div class="text-xs text-dark-500 flex items-center gap-3">
                                    <span class="flex items-center gap-1"><i class="far fa-calendar-alt text-dark-400"></i> {{ $booking->tanggal->format('d/m/Y') }}</span>
                                    <span class="flex items-center gap-1"><i class="far fa-clock text-dark-400"></i> {{ substr($booking->jam_mulai, 0, 5) }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-mono text-sm font-bold text-dark-700">{{ $booking->kode_booking }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-dark-900">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="badge" style="background: {{ in_array($booking->status, ['confirmed', 'completed']) ? 'rgba(204,255,0,0.15)' : (in_array($booking->status, ['pending_payment', 'under_review']) ? 'rgba(0,229,255,0.1)' : 'rgba(239,68,68,0.1)') }}; color: {{ in_array($booking->status, ['confirmed', 'completed']) ? '#526b00' : (in_array($booking->status, ['pending_payment', 'under_review']) ? '#007a8f' : '#dc2626') }}; border: 1px solid {{ in_array($booking->status, ['confirmed', 'completed']) ? 'rgba(204,255,0,0.3)' : (in_array($booking->status, ['pending_payment', 'under_review']) ? 'rgba(0,229,255,0.2)' : 'rgba(239,68,68,0.2)') }};">
                                    {{ $booking->status_label }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                @if(in_array($booking->status, ['pending_payment', 'draft']))
                                <a href="{{ route('member.booking.checkout', $booking->id) }}" class="btn-primary py-2 px-4 text-xs font-bold rounded-xl shadow-none hover:shadow-lg transition-shadow">Bayar</a>
                                @else
                                <a href="{{ route('member.booking.show', $booking->id) }}" class="btn-secondary py-2 px-4 text-xs font-bold rounded-xl shadow-none hover:shadow-sm transition-shadow">Detail</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background: rgba(204,255,0,0.1); color: #6e8f00;">
                                    <i class="fas fa-receipt text-2xl"></i>
                                </div>
                                <p class="text-lg font-bold text-dark-900 mb-1">Belum Ada Transaksi</p>
                                <p class="text-sm text-dark-500">Anda belum pernah melakukan booking lapangan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($bookings->hasPages())
            <div class="p-5 border-t border-dark-100/60" style="background: rgba(0,0,0,0.01);">
                {{ $bookings->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')
@section('title', 'Data Booking')
@section('page_title', 'Data Booking')
@section('page_description', 'Kelola semua transaksi penyewaan lapangan futsal.')

@section('page_actions')
<div class="flex items-center gap-3">
    <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-dark-100 flex items-center gap-2">
        <span class="w-2.5 h-2.5 rounded-full" style="background: #007a8f;"></span>
        <span class="text-sm font-bold text-dark-900">Perlu Review: <span style="color: #007a8f;">{{ \App\Models\Booking::where('status', 'under_review')->count() }}</span></span>
    </div>
</div>
@endsection

@section('content')
<div class="card-premium overflow-hidden">
    <div class="p-6 border-b border-dark-100/60 flex flex-col md:flex-row md:items-center justify-between gap-4" style="background: rgba(0,0,0,0.01);">
        <form action="{{ route('admin.booking.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 w-full md:w-auto flex-1">
            <div class="relative w-full sm:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-dark-400"><i class="fas fa-search"></i></span>
                <input type="text" name="search" value="{{ request('search') }}" class="w-full py-2.5 pl-11 pr-4 text-sm bg-white border border-dark-100/80 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm" placeholder="Cari kode/nama...">
            </div>
            <select name="status" class="py-2.5 px-4 text-sm bg-white border border-dark-100/80 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 shadow-sm outline-none">
                <option value="">Semua Status</option>
                <option value="pending_payment" {{ request('status') == 'pending_payment' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Dalam Pengecekan</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="py-2.5 px-4 text-sm bg-white border border-dark-100/80 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 shadow-sm outline-none">
            <button type="submit" class="btn-primary py-2.5 px-4"><i class="fas fa-filter"></i></button>
            @if(request()->anyFilled(['search', 'status', 'tanggal']))
            <a href="{{ route('admin.booking.index') }}" class="btn-secondary py-2.5 px-4"><i class="fas fa-times"></i></a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="table-modern w-full">
            <thead>
                <tr>
                    <th class="py-4 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider">Kode / Waktu</th>
                    <th class="py-4 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider">Member</th>
                    <th class="py-4 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider">Lapangan & Jadwal</th>
                    <th class="py-4 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider">Total</th>
                    <th class="py-4 px-6 text-center text-xs font-bold text-dark-400 uppercase tracking-wider">Status</th>
                    <th class="py-4 px-6 text-right text-xs font-bold text-dark-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-100/40">
                @forelse($bookings as $booking)
                <tr class="hover:bg-dark-50/50 transition-colors group">
                    <td class="py-4 px-6">
                        <div class="font-mono text-sm font-bold text-dark-900 mb-1">{{ $booking->kode_booking }}</div>
                        <div class="text-xs text-dark-500 flex items-center gap-1.5"><i class="far fa-clock"></i> {{ $booking->created_at->format('d/m/Y H:i') }}</div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="font-bold text-dark-900 text-sm mb-1 line-clamp-1">{{ $booking->user->name }}</div>
                        <div class="text-xs text-dark-500">{{ $booking->user->phone ?? '-' }}</div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="font-bold text-dark-900 text-sm mb-1 line-clamp-1">{{ $booking->lapangan->nama }}</div>
                        <div class="text-xs text-dark-500 flex items-center gap-2">
                            <span class="flex items-center gap-1"><i class="far fa-calendar-alt text-dark-400"></i> {{ $booking->tanggal->format('d/m') }}</span>
                            <span class="flex items-center gap-1"><i class="far fa-clock text-dark-400"></i> {{ substr($booking->jam_mulai, 0, 5) }}</span>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="font-bold text-dark-900 text-sm">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</div>
                        @if($booking->metode_pembayaran)
                            <div class="text-[10px] uppercase font-bold tracking-wider mt-1" style="color: {{ $booking->metode_pembayaran === 'midtrans' ? '#00b3cc' : '#6e8f00' }}">
                                {{ $booking->metode_pembayaran === 'midtrans' ? 'Otomatis' : 'Manual' }}
                            </div>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-center">
                        <span class="badge" style="background: {{ in_array($booking->status, ['confirmed', 'completed']) ? 'rgba(204,255,0,0.15)' : (in_array($booking->status, ['pending_payment', 'under_review']) ? 'rgba(0,229,255,0.1)' : 'rgba(239,68,68,0.1)') }}; color: {{ in_array($booking->status, ['confirmed', 'completed']) ? '#526b00' : (in_array($booking->status, ['pending_payment', 'under_review']) ? '#007a8f' : '#dc2626') }}; border: 1px solid {{ in_array($booking->status, ['confirmed', 'completed']) ? 'rgba(204,255,0,0.3)' : (in_array($booking->status, ['pending_payment', 'under_review']) ? 'rgba(0,229,255,0.2)' : 'rgba(239,68,68,0.2)') }};">
                            {{ $booking->status_label }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-right">
                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('admin.booking.show', $booking->id) }}" class="btn-secondary py-2 px-3 text-xs rounded-lg shadow-none group-hover:shadow-sm">
                                Detail <i class="fas fa-chevron-right ml-1"></i>
                            </a>
                        </div>
                        <div class="md:hidden flex items-center justify-end gap-2 group-hover:hidden">
                            <i class="fas fa-ellipsis-h text-dark-300"></i>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background: rgba(0,0,0,0.03); color: #627d9e;">
                            <i class="fas fa-receipt text-2xl"></i>
                        </div>
                        <p class="text-dark-900 font-bold mb-1">Data tidak ditemukan</p>
                        <p class="text-sm text-dark-500">Belum ada transaksi booking yang sesuai filter.</p>
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
@endsection
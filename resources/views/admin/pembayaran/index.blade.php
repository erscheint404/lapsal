@extends('layouts.admin')
@section('title', 'Verifikasi Pembayaran')
@section('page_title', 'Verifikasi Pembayaran')
@section('page_description', 'Cek dan verifikasi bukti transfer manual dari member.')

@section('content')
<div class="card-premium overflow-hidden">
    <div class="p-6 border-b border-dark-100/60 flex flex-col md:flex-row md:items-center justify-between gap-4" style="background: rgba(0,0,0,0.01);">
        <form action="{{ route('admin.pembayaran.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 w-full md:w-auto flex-1">
            <select name="status" class="py-2.5 px-4 text-sm bg-white border border-dark-100/80 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 shadow-sm outline-none">
                <option value="">Semua Status</option>
                <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Dalam Pengecekan</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Terkonfirmasi (Valid)</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Ditolak / Invalid</option>
            </select>
            <button type="submit" class="btn-primary py-2.5 px-4"><i class="fas fa-filter"></i></button>
            @if(request()->filled('status'))
            <a href="{{ route('admin.pembayaran.index') }}" class="btn-secondary py-2.5 px-4"><i class="fas fa-times"></i></a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="table-modern w-full">
            <thead>
                <tr>
                    <th class="py-4 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider">Booking & Member</th>
                    <th class="py-4 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider">Bukti Transfer</th>
                    <th class="py-4 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider">Total & Waktu</th>
                    <th class="py-4 px-6 text-center text-xs font-bold text-dark-400 uppercase tracking-wider">Status</th>
                    <th class="py-4 px-6 text-right text-xs font-bold text-dark-400 uppercase tracking-wider w-40">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-100/40">
                @forelse($bookings as $booking)
                <tr class="hover:bg-dark-50/50 transition-colors group">
                    <td class="py-4 px-6">
                        <div class="font-mono text-sm font-bold text-dark-900 mb-1 hover:text-primary-600 transition-colors">
                            <a href="{{ route('admin.booking.show', $booking->id) }}">{{ $booking->kode_booking }}</a>
                        </div>
                        <div class="text-xs text-dark-500 flex items-center gap-2">
                            <i class="fas fa-user text-dark-300"></i> {{ $booking->user->name }}
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        @if($booking->buktiPembayaran && $booking->buktiPembayaran->file_path)
                        <a href="{{ Storage::url($booking->buktiPembayaran->file_path) }}" target="_blank" class="inline-block relative overflow-hidden rounded-xl border border-dark-200 group/img">
                            <img src="{{ Storage::url($booking->buktiPembayaran->file_path) }}" class="w-16 h-16 object-cover transition-transform group-hover/img:scale-110">
                            <div class="absolute inset-0 bg-dark-900/40 flex items-center justify-center opacity-0 group-hover/img:opacity-100 transition-opacity">
                                <i class="fas fa-search-plus text-white"></i>
                            </div>
                        </a>
                        @else
                        <span class="text-xs text-dark-400 italic">Tidak ada bukti</span>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <div class="font-bold text-dark-900 text-sm mb-1">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</div>
                        <div class="text-xs text-dark-500"><i class="far fa-clock mr-1"></i> {{ $booking->buktiPembayaran ? $booking->buktiPembayaran->created_at->format('d M H:i') : '-' }}</div>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <span class="badge" style="background: {{ in_array($booking->status, ['confirmed', 'completed']) ? 'rgba(204,255,0,0.15)' : (in_array($booking->status, ['pending_payment', 'under_review']) ? 'rgba(0,229,255,0.1)' : 'rgba(239,68,68,0.1)') }}; color: {{ in_array($booking->status, ['confirmed', 'completed']) ? '#526b00' : (in_array($booking->status, ['pending_payment', 'under_review']) ? '#007a8f' : '#dc2626') }};">
                            {{ $booking->status_label }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-right">
                        @if($booking->status === 'under_review')
                        <div class="flex items-center justify-end gap-2">
                            <form action="{{ route('admin.pembayaran.verify', $booking->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center bg-primary-50 text-primary-600 hover:bg-primary-100 hover:text-primary-700 transition-colors tooltip" data-tip="Terima (Valid)" onclick="return confirm('Validasi pembayaran ini?')">
                                    <i class="fas fa-check text-sm"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.pembayaran.verify', $booking->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="reject">
                                <input type="hidden" name="catatan" value="Bukti transfer tidak valid/tidak jelas">
                                <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-colors tooltip" data-tip="Tolak (Invalid)" onclick="return confirm('Tolak pembayaran ini?')">
                                    <i class="fas fa-times text-sm"></i>
                                </button>
                            </form>
                        </div>
                        @else
                        <a href="{{ route('admin.booking.show', $booking->id) }}" class="btn-secondary py-1.5 px-3 text-xs rounded-lg shadow-none opacity-0 group-hover:opacity-100 transition-opacity">Detail</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-12 text-center">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background: rgba(0,0,0,0.03); color: #627d9e;">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                        <p class="text-dark-900 font-bold mb-1">Semua Selesai</p>
                        <p class="text-sm text-dark-500">Tidak ada pembayaran manual yang perlu diverifikasi.</p>
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
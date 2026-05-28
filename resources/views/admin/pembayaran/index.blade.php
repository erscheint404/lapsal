@extends('layouts.admin')
@section('title', 'Verifikasi Pembayaran')
@section('page_title', 'Verifikasi Pembayaran Manual')

@section('content')
<div class="bg-white rounded-2xl border border-dark-100 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-dark-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-amber-50/50">
        <div>
            <h2 class="font-bold text-lg text-dark-900">Menunggu Verifikasi</h2>
            <p class="text-sm text-dark-500">Cek mutasi bank dan cocokkan dengan bukti transfer yang diupload member.</p>
        </div>
        
        <form action="{{ route('admin.pembayaran.index') }}" method="GET" class="flex gap-2">
            <select name="status" class="form-input py-2 bg-white" onchange="this.form.submit()">
                <option value="under_review" {{ request('status', 'under_review') == 'under_review' ? 'selected' : '' }}>Perlu Review ({{ \App\Models\Booking::where('status', 'under_review')->count() }})</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Sudah Diverifikasi</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                <option value="">Semua Status</option>
            </select>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-dark-50 border-b border-dark-100">
                    <th class="py-4 px-6 text-xs font-bold text-dark-500 uppercase">Waktu Upload</th>
                    <th class="py-4 px-6 text-xs font-bold text-dark-500 uppercase">Booking Info</th>
                    <th class="py-4 px-6 text-xs font-bold text-dark-500 uppercase">Nominal</th>
                    <th class="py-4 px-6 text-xs font-bold text-dark-500 uppercase text-center">Bukti Transfer</th>
                    <th class="py-4 px-6 text-xs font-bold text-dark-500 uppercase text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-100">
                @forelse($bookings as $booking)
                <tr class="hover:bg-dark-50/50 transition-colors">
                    <td class="py-4 px-6">
                        @if($booking->buktiPembayaran)
                        <div class="font-medium text-dark-900">{{ $booking->buktiPembayaran->created_at->format('d/m/Y') }}</div>
                        <div class="text-xs text-dark-500">{{ $booking->buktiPembayaran->created_at->format('H:i') }} WIB</div>
                        <div class="text-[10px] mt-1 text-dark-400">{{ $booking->buktiPembayaran->created_at->diffForHumans() }}</div>
                        @else
                        <span class="text-xs text-dark-400 italic">Belum upload</span>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <div class="font-mono font-bold text-primary-600 mb-1">{{ $booking->kode_booking }}</div>
                        <div class="text-sm font-medium text-dark-900">{{ $booking->user->name }}</div>
                        <div class="text-xs text-dark-500">{{ $booking->lapangan->nama }} ({{ $booking->tanggal->format('d/m/Y') }})</div>
                    </td>
                    <td class="py-4 px-6">
                        <span class="font-bold text-dark-900 text-lg">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                    </td>
                    <td class="py-4 px-6 text-center">
                        @if($booking->buktiPembayaran)
                        <a href="{{ Storage::url($booking->buktiPembayaran->file_path) }}" target="_blank" class="inline-block relative group">
                            <div class="w-16 h-16 rounded-xl bg-dark-100 overflow-hidden border-2 border-dark-200 group-hover:border-primary-500 transition-colors">
                                <img src="{{ Storage::url($booking->buktiPembayaran->file_path) }}" class="w-full h-full object-cover">
                            </div>
                            <div class="absolute inset-0 bg-dark-900/40 rounded-xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="fas fa-search-plus text-white"></i>
                            </div>
                        </a>
                        @else
                        -
                        @endif
                    </td>
                    <td class="py-4 px-6 text-right">
                        @if($booking->status === 'under_review')
                        <a href="{{ route('admin.booking.show', $booking->id) }}" class="btn-primary py-1.5 px-4 text-xs bg-amber-500 hover:bg-amber-600 border-none shadow-md shadow-amber-500/20 whitespace-nowrap">
                            Review <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                        @else
                        <span class="badge {{ $booking->status_color }}">{{ $booking->status_label }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-12 text-center text-dark-500">
                        <div class="w-16 h-16 bg-dark-50 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl text-dark-300">
                            <i class="fas fa-check-double"></i>
                        </div>
                        Tidak ada antrean verifikasi pembayaran saat ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-6 border-t border-dark-100">
        {{ $bookings->links() }}
    </div>
</div>
@endsection

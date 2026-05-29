@extends('layouts.admin')
@section('title', 'Detail Booking')
@section('page_title', 'Detail Booking #' . $booking->kode_booking)
@section('page_description', 'Lihat informasi lengkap dan kelola status penyewaan ini.')

@section('page_actions')
<a href="{{ route('admin.booking.index') }}" class="btn-secondary">
    <i class="fas fa-arrow-left mr-2"></i> Kembali
</a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Main Area --}}
    <div class="lg:col-span-2 space-y-6">
        
        {{-- Status Banner --}}
        <div class="rounded-3xl p-6 relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-sm border"
             style="background: {{ in_array($booking->status, ['confirmed', 'completed']) ? 'rgba(204,255,0,0.05)' : (in_array($booking->status, ['pending_payment', 'under_review']) ? 'rgba(0,229,255,0.05)' : 'rgba(239,68,68,0.05)') }};
                    border-color: {{ in_array($booking->status, ['confirmed', 'completed']) ? 'rgba(204,255,0,0.2)' : (in_array($booking->status, ['pending_payment', 'under_review']) ? 'rgba(0,229,255,0.2)' : 'rgba(239,68,68,0.2)') }};">
            
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 shadow-sm"
                     style="background: white; border: 1px solid {{ in_array($booking->status, ['confirmed', 'completed']) ? 'rgba(204,255,0,0.3)' : (in_array($booking->status, ['pending_payment', 'under_review']) ? 'rgba(0,229,255,0.3)' : 'rgba(239,68,68,0.3)') }};
                            color: {{ in_array($booking->status, ['confirmed', 'completed']) ? '#526b00' : (in_array($booking->status, ['pending_payment', 'under_review']) ? '#007a8f' : '#dc2626') }};">
                    <i class="fas {{ in_array($booking->status, ['confirmed', 'completed']) ? 'fa-check' : (in_array($booking->status, ['pending_payment', 'under_review']) ? 'fa-clock' : 'fa-times') }} text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider mb-1 text-dark-500">Status Terkini</p>
                    <h2 class="text-xl font-black" style="color: {{ in_array($booking->status, ['confirmed', 'completed']) ? '#526b00' : (in_array($booking->status, ['pending_payment', 'under_review']) ? '#007a8f' : '#dc2626') }};">
                        {{ $booking->status_label }}
                    </h2>
                </div>
            </div>

            <div class="flex flex-wrap gap-2">
                @if($booking->status === 'under_review')
                <form action="{{ route('admin.booking.update-status', $booking->id) }}" method="POST" class="inline-block">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="confirmed">
                    <button type="submit" class="btn-primary py-2 px-4 shadow-sm" onclick="return confirm('Konfirmasi booking ini?')">Terima</button>
                </form>
                <form action="{{ route('admin.booking.update-status', $booking->id) }}" method="POST" class="inline-block">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit" class="btn-secondary py-2 px-4 border-red-200 text-red-600 hover:bg-red-50" onclick="return confirm('Tolak booking ini?')">Tolak</button>
                </form>
                @endif
                
                @if($booking->status === 'confirmed')
                <form action="{{ route('admin.booking.update-status', $booking->id) }}" method="POST" class="inline-block">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="btn-secondary py-2 px-4 border-blue-200 text-blue-600 hover:bg-blue-50" onclick="return confirm('Tandai booking selesai?')">Tandai Selesai</button>
                </form>
                @endif
            </div>
        </div>

        {{-- Detail Informasi --}}
        <div class="card-premium p-8">
            <h3 class="font-bold text-lg text-dark-900 mb-6 border-b border-dark-100/60 pb-3">Informasi Booking</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs font-bold text-dark-400 uppercase tracking-wider mb-1">Kode Booking</p>
                    <p class="font-mono font-black text-lg text-dark-900">{{ $booking->kode_booking }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-dark-400 uppercase tracking-wider mb-1">Tanggal Pemesanan</p>
                    <p class="font-bold text-dark-900">{{ $booking->created_at->format('d M Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-dark-400 uppercase tracking-wider mb-1">Member</p>
                    <div class="flex items-center gap-3 mt-1">
                        <img src="{{ $booking->user->avatar_url }}" class="w-8 h-8 rounded-full object-cover bg-dark-100">
                        <div>
                            <p class="font-bold text-dark-900 leading-tight">{{ $booking->user->name }}</p>
                            <p class="text-xs text-dark-500">{{ $booking->user->phone ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-bold text-dark-400 uppercase tracking-wider mb-1">Email</p>
                    <p class="font-medium text-dark-900 mt-2">{{ $booking->user->email }}</p>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-dark-100/60">
                <h4 class="font-bold text-dark-900 mb-4">Jadwal Main</h4>
                <div class="flex gap-4 p-4 rounded-2xl border border-dark-100/60" style="background: rgba(0,0,0,0.02);">
                    <div class="w-16 h-16 rounded-xl overflow-hidden shrink-0 border border-dark-100">
                        @if($booking->lapangan->foto_utama)
                        <img src="{{ Storage::url($booking->lapangan->foto_utama) }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full bg-dark-50 flex items-center justify-center"><i class="fas fa-image text-dark-300"></i></div>
                        @endif
                    </div>
                    <div>
                        <p class="font-bold text-dark-900 text-lg mb-1">{{ $booking->lapangan->nama }}</p>
                        <div class="flex flex-wrap items-center gap-4 text-sm font-medium text-dark-600">
                            <span class="flex items-center gap-1.5"><i class="far fa-calendar-alt text-dark-400"></i> {{ $booking->tanggal->format('d M Y') }}</span>
                            <span class="flex items-center gap-1.5"><i class="far fa-clock text-dark-400"></i> {{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }}</span>
                            <span class="flex items-center gap-1.5"><i class="fas fa-hourglass-half text-dark-400"></i> {{ $booking->durasi }} Jam</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="lg:col-span-1 space-y-6">
        
        {{-- Payment Detail --}}
        <div class="card-premium p-6">
            <h3 class="font-bold text-lg text-dark-900 mb-5 border-b border-dark-100/60 pb-3">Pembayaran</h3>
            
            <div class="bg-dark-900 p-4 rounded-2xl text-white mb-5 relative overflow-hidden">
                <div class="absolute inset-0 dot-pattern opacity-10"></div>
                <div class="absolute top-0 right-0 w-24 h-24 rounded-full blur-[30px]" style="background: rgba(204,255,0,0.15);"></div>
                <div class="relative z-10">
                    <p class="text-xs font-bold uppercase tracking-wider text-dark-400 mb-1">Total Tagihan</p>
                    <p class="text-2xl font-black" style="color: #ccff00;">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                </div>
            </div>

            @if($booking->metode_pembayaran)
            <div class="space-y-4 text-sm">
                <div class="flex justify-between pb-3 border-b border-dark-100/40">
                    <span class="text-dark-500">Metode</span>
                    <span class="font-bold text-dark-900">{{ $booking->metode_pembayaran === 'midtrans' ? 'Otomatis (Midtrans)' : 'Transfer Manual' }}</span>
                </div>
                @if($booking->metode_pembayaran === 'manual' && $booking->buktiPembayaran)
                <div class="flex justify-between pb-3 border-b border-dark-100/40">
                    <span class="text-dark-500">Waktu Bayar</span>
                    <span class="font-bold text-dark-900">{{ $booking->buktiPembayaran->created_at->format('d M H:i') }}</span>
                </div>
                @endif
                <div class="flex justify-between items-center pt-1">
                    <span class="text-dark-500">Status</span>
                    <span class="badge" style="background: {{ in_array($booking->status, ['confirmed', 'completed']) ? 'rgba(204,255,0,0.15)' : (in_array($booking->status, ['pending_payment', 'under_review']) ? 'rgba(0,229,255,0.1)' : 'rgba(239,68,68,0.1)') }}; color: {{ in_array($booking->status, ['confirmed', 'completed']) ? '#526b00' : (in_array($booking->status, ['pending_payment', 'under_review']) ? '#007a8f' : '#dc2626') }};">
                        {{ $booking->status_label }}
                    </span>
                </div>
            </div>
            
            @if($booking->metode_pembayaran === 'manual' && $booking->buktiPembayaran && $booking->buktiPembayaran->file_path)
            <div class="mt-6">
                <p class="text-xs font-bold text-dark-500 uppercase tracking-wider mb-2">Bukti Transfer:</p>
                <a href="{{ Storage::url($booking->buktiPembayaran->file_path) }}" target="_blank" class="block rounded-xl overflow-hidden border border-dark-200 hover:opacity-90 transition-opacity">
                    <img src="{{ Storage::url($booking->buktiPembayaran->file_path) }}" class="w-full h-32 object-cover">
                </a>
            </div>
            @endif
            @else
            <div class="text-center py-4 rounded-xl text-amber-700 bg-amber-50 border border-amber-200 text-sm font-medium">
                Belum ada data pembayaran
            </div>
            @endif
        </div>

        {{-- Ticket Actions --}}
        @if(in_array($booking->status, ['confirmed', 'completed']))
        <div class="card-premium p-6 text-center">
            <h3 class="font-bold text-lg text-dark-900 mb-4 border-b border-dark-100/60 pb-3">E-Ticket</h3>
            <div class="bg-white p-3 rounded-2xl shadow-sm border border-dark-100 inline-block mb-4">
                {!! QrCode::size(120)->color(10,18,33)->generate($booking->kode_booking) !!}
            </div>
            <p class="font-mono font-bold text-dark-600 mb-4">{{ $booking->kode_booking }}</p>
            <a href="#" class="btn-secondary w-full" onclick="window.print(); return false;"><i class="fas fa-print mr-2"></i>Cetak Tiket</a>
        </div>
        @endif
    </div>
</div>
@endsection
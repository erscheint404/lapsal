@extends('layouts.app')
@section('title', 'Detail Booking')
@section('content')
<div class="section-gradient min-h-screen pt-28 pb-16">
    <div class="container-custom max-w-5xl">

        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4 reveal">
            <div>
                <a href="{{ route('member.dashboard') }}" class="text-sm font-bold text-dark-500 hover:text-primary-500 mb-3 inline-block transition-colors"><i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard</a>
                <h1 class="text-3xl font-display font-black text-dark-900 tracking-tight flex items-center gap-3">
                    Detail Booking
                    <span class="badge" style="background: {{ in_array($booking->status, ['confirmed']) ? 'rgba(204,255,0,0.15)' : (in_array($booking->status, ['pending_payment', 'under_review']) ? 'rgba(0,229,255,0.1)' : 'rgba(0,0,0,0.05)') }}; color: {{ in_array($booking->status, ['confirmed']) ? '#526b00' : (in_array($booking->status, ['pending_payment', 'under_review']) ? '#007a8f' : '#46607f') }}; font-size: 0.875rem; padding: 0.35rem 0.75rem;">
                        {{ $booking->status_label }}
                    </span>
                </h1>
            </div>
            @if($booking->status === 'pending_payment')
            <a href="{{ route('member.booking.checkout', $booking->id) }}" class="btn-primary shadow-xl">
                Bayar Sekarang <i class="fas fa-arrow-right ml-2"></i>
            </a>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Info --}}
            <div class="lg:col-span-2 space-y-8 reveal-left">
                {{-- Ticket Card (if confirmed) --}}
                @if($booking->status === 'confirmed')
                <div class="rounded-3xl overflow-hidden shadow-2xl relative group" style="background: linear-gradient(135deg, #0a1221, #1a2740);">
                    <div class="absolute inset-0 dot-pattern opacity-10"></div>
                    <div class="absolute top-0 right-0 w-64 h-64 rounded-full blur-[80px]" style="background: rgba(204,255,0,0.15);"></div>
                    <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full blur-[80px]" style="background: rgba(0,229,255,0.1);"></div>

                    <div class="flex flex-col md:flex-row relative z-10">
                        {{-- QR Section --}}
                        <div class="p-8 md:border-r border-dashed border-white/20 flex flex-col items-center justify-center bg-white/5 backdrop-blur-md w-full md:w-1/3">
                            <p class="text-xs font-bold text-dark-300 uppercase tracking-widest mb-4">Tiket Masuk</p>
                            <div class="bg-white p-3 rounded-2xl shadow-xl mb-4 transform transition-transform group-hover:scale-105">
                                {!! QrCode::size(140)->color(10,18,33)->generate($booking->kode_booking) !!}
                            </div>
                            <p class="font-mono text-lg font-black tracking-widest" style="color: #ccff00; text-shadow: 0 0 10px rgba(204,255,0,0.4);">{{ $booking->kode_booking }}</p>
                        </div>
                        {{-- Info Section --}}
                        <div class="p-8 flex-1 flex flex-col justify-center">
                            <h3 class="text-3xl font-display font-black text-white mb-2">{{ $booking->lapangan->nama }}</h3>
                            <p class="text-sm font-bold text-dark-300 mb-6 uppercase tracking-wider">{{ \App\Models\Pengaturan::getValue('nama_web', 'Lapsal') }}</p>

                            <div class="grid grid-cols-2 gap-y-6 gap-x-4">
                                <div>
                                    <p class="text-[10px] uppercase font-bold text-dark-400 mb-1">Tanggal</p>
                                    <p class="font-bold text-white text-lg">{{ $booking->tanggal->format('d M Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] uppercase font-bold text-dark-400 mb-1">Waktu</p>
                                    <p class="font-bold text-white text-lg">{{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] uppercase font-bold text-dark-400 mb-1">Pemesanan Atas Nama</p>
                                    <p class="font-bold text-white text-lg truncate">{{ $booking->user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] uppercase font-bold text-dark-400 mb-1">Status</p>
                                    <p class="font-bold text-lg" style="color: #ccff00;"><i class="fas fa-check-circle mr-1"></i> Valid</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Cutout effect --}}
                    <div class="absolute -top-3 left-[33%] md:left-1/3 w-6 h-6 bg-[#f8fafc] rounded-full hidden md:block"></div>
                    <div class="absolute -bottom-3 left-[33%] md:left-1/3 w-6 h-6 bg-[#f8fafc] rounded-full hidden md:block"></div>
                </div>
                @endif

                {{-- Payment Verification Notice --}}
                @if($booking->status === 'under_review')
                <div class="p-6 rounded-2xl flex gap-4 items-start shadow-sm border" style="background: rgba(0,229,255,0.05); border-color: rgba(0,229,255,0.2);">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background: rgba(0,229,255,0.15); color: #007a8f;">
                        <i class="fas fa-search-dollar text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-dark-900 text-lg mb-1">Pembayaran Sedang Diverifikasi</h4>
                        <p class="text-sm text-dark-600 leading-relaxed">Admin kami sedang mengecek bukti pembayaran Anda. Proses ini biasanya memakan waktu 5-15 menit. Silakan refresh halaman ini secara berkala.</p>
                    </div>
                </div>
                @endif

                {{-- Detail Pemesanan --}}
                <div class="card-premium p-8">
                    <h3 class="text-xl font-bold text-dark-900 mb-6 border-b border-dark-100/60 pb-4">Informasi Lapangan & Waktu</h3>

                    <div class="flex gap-5 mb-8">
                        <div class="w-24 h-24 rounded-2xl overflow-hidden shrink-0 border border-dark-100/60 hidden sm:block">
                            @if($booking->lapangan->foto_utama)
                            <img src="{{ Storage::url($booking->lapangan->foto_utama) }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full bg-dark-50 flex items-center justify-center"><i class="fas fa-image text-dark-300"></i></div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-dark-900 text-xl mb-1">{{ $booking->lapangan->nama }}</h4>
                            <span class="badge badge-info mb-4">{{ ucfirst(str_replace('_', ' ', $booking->lapangan->tipe)) }}</span>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-dark-50 p-3 rounded-xl border border-dark-100/40">
                                    <p class="text-xs font-bold text-dark-400 uppercase tracking-wider mb-1">Tanggal Main</p>
                                    <p class="font-bold text-dark-900"><i class="far fa-calendar-alt mr-1.5" style="color: #6e8f00;"></i>{{ $booking->tanggal->format('l, d F Y') }}</p>
                                </div>
                                <div class="bg-dark-50 p-3 rounded-xl border border-dark-100/40">
                                    <p class="text-xs font-bold text-dark-400 uppercase tracking-wider mb-1">Waktu Main</p>
                                    <p class="font-bold text-dark-900"><i class="far fa-clock mr-1.5" style="color: #6e8f00;"></i>{{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }} WIB</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h3 class="text-xl font-bold text-dark-900 mb-6 border-b border-dark-100/60 pb-4">Rincian Harga</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-dark-600 font-medium">Harga per jam</span>
                            <span class="font-bold text-dark-900">Rp {{ number_format($booking->lapangan->harga_per_jam, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-dark-600 font-medium">Durasi</span>
                            <span class="font-bold text-dark-900">{{ $booking->durasi }} Jam</span>
                        </div>
                        <div class="flex justify-between items-center pt-4" style="border-top: 1px dashed rgba(0,0,0,0.1);">
                            <span class="text-lg font-bold text-dark-900">Total Harga</span>
                            <span class="text-2xl font-black" style="color: #526b00;">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Rating & Ulasan Section --}}
                @if($booking->status === 'completed')
                <div class="card-premium p-8" id="rating">
                    <h3 class="text-xl font-bold text-dark-900 mb-6 flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: rgba(204,255,0,0.15); color: #6e8f00;"><i class="fas fa-star"></i></div>
                        Ulasan Lapangan
                    </h3>

                    @if($booking->rating)
                    <div class="bg-dark-50 rounded-2xl p-6 border border-dark-100/60 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-6 text-5xl opacity-5"><i class="fas fa-quote-right"></i></div>
                        <div class="flex items-center gap-1 mb-4" style="color: #a3cc00;">
                            @for($i = 0; $i < $booking->rating->rating; $i++) <i class="fas fa-star text-lg"></i> @endfor
                            @for($i = 0; $i < 5 - $booking->rating->rating; $i++) <i class="far fa-star text-lg text-dark-200"></i> @endfor
                        </div>
                        <p class="text-dark-700 italic font-medium relative z-10">"{{ $booking->rating->ulasan ?: 'Tidak ada komentar tertulis.' }}"</p>
                        <p class="text-xs text-dark-400 mt-4">{{ $booking->rating->created_at->format('d M Y H:i') }}</p>
                    </div>
                    @else
                    <form action="{{ route('member.booking.rating', $booking->id) }}" method="POST">
                        @csrf
                        <div class="mb-5">
                            <label class="form-label block mb-2">Beri Rating (1-5)</label>
                            <div class="flex gap-2 flex-row-reverse justify-end peer" x-data="{ rating: 0, hover: 0 }">
                                @for($i = 5; $i >= 1; $i--)
                                <label class="cursor-pointer">
                                    <input type="radio" name="rating" value="{{ $i }}" class="peer sr-only" required @click="rating = {{ $i }}">
                                    <i class="fas fa-star text-3xl transition-colors cursor-pointer"
                                       :class="(hover >= {{ $i }} || rating >= {{ $i }}) ? 'text-primary-500' : 'text-dark-200'"
                                       @mouseenter="hover = {{ $i }}" @mouseleave="hover = 0"></i>
                                </label>
                                @endfor
                            </div>
                        </div>
                        <div class="mb-5">
                            <label class="form-label">Ulasan Singkat (Opsional)</label>
                            <textarea name="ulasan" class="form-textarea" placeholder="Bagaimana pengalaman bermain di lapangan ini?"></textarea>
                        </div>
                        <button type="submit" class="btn-primary w-full shadow-lg shadow-primary-500/20">Kirim Ulasan</button>
                    </form>
                    @endif
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1 reveal-right">
                {{-- Payment Detail --}}
                @if($booking->buktiPembayaran || $booking->metode_pembayaran === 'midtrans')
                <div class="card-premium p-6 sticky top-28 shadow-xl">
                    <h3 class="font-bold text-lg text-dark-900 mb-5 border-b border-dark-100/60 pb-3">Detail Pembayaran</h3>

                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between">
                            <span class="text-dark-500">Metode</span>
                            <span class="font-bold text-dark-900">{{ $booking->metode_pembayaran === 'midtrans' ? 'Otomatis (Midtrans)' : 'Transfer Manual' }}</span>
                        </div>
                        @if($booking->buktiPembayaran)
                        <div class="flex justify-between">
                            <span class="text-dark-500">Tanggal Bayar</span>
                            <span class="font-bold text-dark-900">{{ $booking->buktiPembayaran->created_at->format('d M Y H:i') }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-dark-500">Nominal</span>
                            <span class="font-bold text-dark-900">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2">
                            <span class="text-dark-500">Status</span>
                            <span class="badge" style="background: {{ in_array($booking->status, ['confirmed', 'completed']) ? 'rgba(204,255,0,0.15)' : (in_array($booking->status, ['pending_payment', 'under_review']) ? 'rgba(245,158,11,0.1)' : 'rgba(239,68,68,0.1)') }}; color: {{ in_array($booking->status, ['confirmed', 'completed']) ? '#526b00' : (in_array($booking->status, ['pending_payment', 'under_review']) ? '#d97706' : '#dc2626') }};">
                                {{ $booking->status_label }}
                            </span>
                        </div>
                    </div>

                    @if($booking->metode_pembayaran === 'manual' && $booking->buktiPembayaran && $booking->buktiPembayaran->file_path)
                    <div class="mt-6 border-t border-dark-100/60 pt-4">
                        <p class="text-sm font-bold text-dark-700 mb-2">Bukti Transfer:</p>
                        <a href="{{ Storage::url($booking->buktiPembayaran->file_path) }}" target="_blank" class="block rounded-xl overflow-hidden border border-dark-200 hover:opacity-90 transition-opacity">
                            <img src="{{ Storage::url($booking->buktiPembayaran->file_path) }}" alt="Bukti Transfer" class="w-full h-32 object-cover">
                        </a>
                    </div>
                    @endif

                    @if($booking->metode_pembayaran === 'midtrans' && $booking->status === 'pending_payment')
                    <div class="mt-6 border-t border-dark-100/60 pt-4">
                        <button id="pay-button" class="btn-primary w-full text-sm py-3 shadow-lg">Lanjutkan Pembayaran Midtrans</button>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(isset($snapToken) && $booking->metode_pembayaran === 'midtrans' && $booking->status === 'pending_payment')
@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    document.getElementById('pay-button').onclick = function(){
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                // In local dev, we append the result to trigger the webhook fallback in controller
                window.location.href = "{{ route('member.booking.show', $booking->id) }}?transaction_status=" + result.transaction_status + "&order_id=" + result.order_id;
            },
            onPending: function(result){
                alert("Menunggu pembayaran Anda!");
            },
            onError: function(result){
                alert("Pembayaran gagal!");
            },
            onClose: function(){
                console.log('Customer closed the popup without finishing the payment');
            }
        });
    };
</script>
@endpush
@endif
@endsection
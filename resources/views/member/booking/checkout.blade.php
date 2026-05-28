@extends('layouts.app')
@section('title', 'Checkout Booking')
@section('content')
<div class="bg-dark-50 py-12 min-h-screen">
    <div class="container-custom max-w-4xl">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-display font-bold text-dark-900 mb-2">Selesaikan Pembayaran</h1>
            <p class="text-dark-500">Kode Booking: <span class="font-mono font-bold text-dark-900">{{ $booking->kode_booking }}</span></p>
        </div>

        {{-- Countdown Timer --}}
        @if($remainingSeconds > 0)
        <div class="mb-8 max-w-md mx-auto" x-data="countdownTimer({{ $remainingSeconds }})">
            <div class="bg-red-50 border-2 border-red-200 rounded-2xl p-4 text-center" :class="{'animate-pulse bg-red-100': seconds < 60}">
                <p class="text-sm font-bold text-red-600 mb-1 uppercase tracking-widest">Sisa Waktu Pembayaran</p>
                <div class="text-3xl font-display font-black text-red-700 flex items-center justify-center gap-2">
                    <i class="far fa-clock"></i>
                    <span x-text="formatTime(seconds)"></span>
                </div>
                <p class="text-xs text-red-500 mt-2">Selesaikan pembayaran sebelum waktu habis agar slot tidak dibatalkan otomatis.</p>
            </div>
        </div>
        @else
        <div class="mb-8 max-w-md mx-auto">
            <div class="bg-red-50 border border-red-200 rounded-2xl p-4 text-center">
                <p class="font-bold text-red-700 mb-1">Waktu Pembayaran Habis</p>
                <p class="text-sm text-red-600">Booking ini telah kadaluarsa karena melewati batas waktu.</p>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Ringkasan Booking --}}
            <div class="space-y-6">
                <div class="card p-6">
                    <h3 class="font-bold text-lg border-b border-dark-100 pb-4 mb-4">Ringkasan Pesanan</h3>
                    
                    <div class="flex gap-4 mb-6">
                        <div class="w-20 h-20 rounded-xl bg-dark-100 overflow-hidden flex-none">
                            @if($booking->lapangan->foto_utama)
                            <img src="{{ Storage::url($booking->lapangan->foto_utama) }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div>
                            <p class="font-bold text-dark-900 text-lg leading-tight">{{ $booking->lapangan->nama }}</p>
                            <span class="badge badge-success mt-1">{{ ucfirst(str_replace('_', ' ', $booking->lapangan->tipe)) }}</span>
                        </div>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-dark-500">Tanggal</span>
                            <span class="font-semibold text-dark-900">{{ $booking->tanggal->translatedFormat('l, d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-dark-500">Waktu</span>
                            <span class="font-semibold text-dark-900">{{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }} WIB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-dark-500">Durasi</span>
                            <span class="font-semibold text-dark-900">{{ $booking->durasi_jam }} Jam</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-dark-500">Harga / Jam</span>
                            <span class="font-semibold text-dark-900">Rp {{ number_format($booking->lapangan->harga_per_jam, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-dashed border-dark-200 flex justify-between items-center">
                        <span class="font-bold text-dark-900">Total Pembayaran</span>
                        <span class="text-2xl font-black text-primary-600">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if($booking->canBeCancelled())
                <form action="{{ route('member.booking.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan booking ini?');">
                    @csrf
                    <button type="submit" class="w-full text-center py-3 text-sm font-bold text-dark-500 hover:text-red-500 transition-colors">
                        Batalkan Booking
                    </button>
                </form>
                @endif
            </div>

            {{-- Metode Pembayaran --}}
            <div>
                @if($remainingSeconds > 0)
                    <div class="card p-6 h-full flex flex-col">
                        <h3 class="font-bold text-lg border-b border-dark-100 pb-4 mb-4">Pilih Pembayaran</h3>
                        
                        <div class="flex-1 space-y-4" x-data="{ method: '{{ $booking->metode_pembayaran }}' }">
                            
                            {{-- Midtrans (Otomatis) --}}
                            <label class="block relative cursor-pointer group">
                                <input type="radio" name="metode" value="midtrans" x-model="method" class="peer sr-only">
                                <div class="p-4 rounded-xl border-2 transition-all duration-200 peer-checked:border-primary-500 peer-checked:bg-primary-50 bg-white border-dark-200 group-hover:border-primary-300">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-3">
                                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center peer-checked:border-primary-500 border-dark-300" :class="method === 'midtrans' ? 'border-primary-500' : ''">
                                                <div class="w-2.5 h-2.5 rounded-full bg-primary-500 transform scale-0 transition-transform" :class="method === 'midtrans' ? 'scale-100' : ''"></div>
                                            </div>
                                            <span class="font-bold text-dark-900">Otomatis (Midtrans)</span>
                                        </div>
                                        <span class="badge badge-success text-xs">Rekomendasi</span>
                                    </div>
                                    <p class="text-sm text-dark-500 ml-8">Bayar pakai Gopay, Qris, Virtual Account BCA/Mandiri/BNI. Konfirmasi instan.</p>
                                </div>
                            </label>

                            {{-- Manual Transfer --}}
                            <label class="block relative cursor-pointer group">
                                <input type="radio" name="metode" value="manual" x-model="method" class="peer sr-only">
                                <div class="p-4 rounded-xl border-2 transition-all duration-200 peer-checked:border-primary-500 peer-checked:bg-primary-50 bg-white border-dark-200 group-hover:border-primary-300">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center border-dark-300" :class="method === 'manual' ? 'border-primary-500' : ''">
                                            <div class="w-2.5 h-2.5 rounded-full bg-primary-500 transform scale-0 transition-transform" :class="method === 'manual' ? 'scale-100' : ''"></div>
                                        </div>
                                        <span class="font-bold text-dark-900">Transfer Manual</span>
                                    </div>
                                    <p class="text-sm text-dark-500 ml-8">Transfer manual ke rekening pengelola. Membutuhkan verifikasi admin (1-2 jam).</p>
                                </div>
                            </label>

                            {{-- Payment Actions --}}
                            <div class="mt-8 pt-4">
                                {{-- Midtrans Button --}}
                                <div x-show="method === 'midtrans'">
                                    <button id="pay-button" class="btn-primary w-full py-4 text-lg shadow-lg shadow-primary-500/30">
                                        Bayar Sekarang <i class="fas fa-lock ml-2 text-xs opacity-70"></i>
                                    </button>
                                </div>

                                {{-- Manual Form --}}
                                <div x-show="method === 'manual'" x-transition>
                                    <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl mb-4">
                                        <p class="text-xs font-bold text-blue-800 uppercase tracking-wider mb-2">Transfer ke Rekening Berikut:</p>
                                        <p class="font-bold text-dark-900">BCA - 1234567890</p>
                                        <p class="text-sm text-dark-600">A/N Lapsal Futsal</p>
                                    </div>

                                    <form action="{{ route('member.booking.payment.manual', $booking->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-4">
                                            <label class="form-label">Upload Bukti Transfer</label>
                                            <input type="file" name="bukti_transfer" class="form-input p-2 text-sm" accept="image/*" required>
                                            <p class="form-help">Format: JPG, PNG. Maksimal 5MB.</p>
                                        </div>
                                        <button type="submit" class="btn-primary w-full">Kirim Bukti Pembayaran</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card p-8 flex flex-col items-center justify-center text-center h-full">
                        <div class="w-20 h-20 bg-dark-50 rounded-full flex items-center justify-center mb-4 border-2 border-dark-100 text-dark-300">
                            <i class="fas fa-times text-3xl"></i>
                        </div>
                        <h3 class="font-bold text-dark-900 mb-2">Tidak Dapat Membayar</h3>
                        <p class="text-dark-500 text-sm">Waktu pembayaran telah habis atau booking sudah dibatalkan.</p>
                        <a href="{{ route('lapangan.index') }}" class="btn-primary mt-6">Buat Booking Baru</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
@if($booking->metode_pembayaran === 'midtrans' && $snapToken && $remainingSeconds > 0)
<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    document.getElementById('pay-button').onclick = function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                window.location.href = "{{ route('member.booking.show', $booking->id) }}";
            },
            onPending: function(result) {
                window.location.href = "{{ route('member.booking.show', $booking->id) }}";
            },
            onError: function(result) {
                alert("Pembayaran gagal!");
            },
            onClose: function() {
                // Modal ditutup
            }
        });
    };
</script>
@endif

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('countdownTimer', (initialSeconds) => ({
        seconds: initialSeconds,
        timer: null,
        
        init() {
            if (this.seconds > 0) {
                this.timer = setInterval(() => {
                    this.seconds--;
                    if (this.seconds <= 0) {
                        clearInterval(this.timer);
                        window.location.reload();
                    }
                }, 1000);
            }
        },
        
        formatTime(sec) {
            let m = Math.floor(sec / 60);
            let s = sec % 60;
            return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
        }
    }));
});
</script>
@endpush
@endsection

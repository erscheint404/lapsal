@extends('layouts.app')
@section('title', 'Konfirmasi Booking')

@section('content')
<div class="bg-gradient-subtle py-12 min-h-[calc(100vh-80px)]">
    <div class="container-custom max-w-5xl">
        <div class="mb-10">
            <h1 class="text-3xl lg:text-4xl font-display font-black text-dark-900 tracking-tight">Konfirmasi Booking</h1>
            <p class="text-dark-500 mt-2 leading-relaxed">Periksa kembali detail pesanan Anda sebelum melanjutkan ke pembayaran.</p>
        </div>

        @if(!$selectedLapangan || $slots->isEmpty())
            <div class="bg-red-50 text-red-600 p-8 rounded-3xl border border-red-200/80 text-center">
                <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
                <h3 class="text-xl font-bold mb-2">Data Booking Tidak Valid</h3>
                <p class="text-red-500">Silakan kembali dan pilih lapangan serta jam yang tersedia.</p>
                <a href="{{ route('lapangan.index') }}" class="btn-primary mt-8">Kembali ke Daftar Lapangan</a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-2 space-y-6">
                    <div class="card-premium p-7 md:p-8">
                        <h3 class="text-lg font-bold text-dark-900 mb-5 pb-4 border-b border-dark-100/80">Detail Lapangan</h3>
                        <div class="flex gap-5 items-start">
                            <div class="w-24 h-24 rounded-2xl overflow-hidden bg-dark-100 flex-none hidden sm:block shadow-sm">
                                @if($selectedLapangan->foto_utama)
                                    <img src="{{ Storage::url($selectedLapangan->foto_utama) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-dark-300"><i class="fas fa-image text-2xl"></i></div>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-dark-900 mb-1">{{ $selectedLapangan->nama }}</h4>
                                <span class="badge badge-success mb-2">{{ ucfirst(str_replace('_', ' ', $selectedLapangan->tipe)) }}</span>
                                <p class="text-primary-600 font-bold text-lg mt-2">Rp {{ number_format($selectedLapangan->harga_per_jam, 0, ',', '.') }} <span class="text-dark-400 font-normal text-sm">/ jam</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="card-premium p-7 md:p-8">
                        <h3 class="text-lg font-bold text-dark-900 mb-5 pb-4 border-b border-dark-100/80">Jadwal Terpilih</h3>
                        <div class="flex items-center gap-4 mb-5 text-dark-700 bg-primary-50/80 p-5 rounded-2xl border border-primary-100/80">
                            <div class="w-12 h-12 rounded-xl bg-primary-100 flex items-center justify-center text-primary-600 text-xl">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-primary-600 uppercase tracking-wider">Tanggal Main</p>
                                <p class="font-bold text-lg">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($slots as $slot)
                                <div class="bg-dark-50 border border-dark-200/80 text-dark-700 text-center py-2.5 px-3 rounded-xl font-medium text-sm hover:border-primary-300 transition-colors">
                                    {{ substr($slot->jam_mulai, 0, 5) }} - {{ substr($slot->jam_selesai, 0, 5) }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="md:col-span-1">
                    <form action="{{ route('member.booking.store') }}" method="POST" class="card-premium p-7 sticky top-24 shadow-xl" x-data="{ metode: 'midtrans' }" id="booking-form">
                        @csrf
                        <input type="hidden" name="lapangan_id" value="{{ $selectedLapangan->id }}">
                        <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                        @foreach($slots as $slot)
                            <input type="hidden" name="slot_ids[]" value="{{ $slot->id }}">
                        @endforeach

                        <h3 class="text-lg font-bold text-dark-900 mb-5 pb-4 border-b border-dark-100/80">Ringkasan Biaya</h3>
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm text-dark-600">
                                <span>Durasi Main</span>
                                <span class="font-semibold text-dark-900">{{ count($slots) }} Jam</span>
                            </div>
                            <div class="flex justify-between text-sm text-dark-600">
                                <span>Harga per Jam</span>
                                <span class="font-semibold text-dark-900">Rp {{ number_format($selectedLapangan->harga_per_jam, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between font-black text-lg text-primary-700 pt-4 mt-4 border-t border-dark-100/80">
                                <span>Total Tagihan</span>
                                <span>Rp {{ number_format(count($slots) * $selectedLapangan->harga_per_jam, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <h3 class="text-sm font-bold text-dark-900 mb-4">Metode Pembayaran</h3>
                        <div class="space-y-3 mb-6">
                            <label class="block relative cursor-pointer group">
                                <input type="radio" name="metode_pembayaran" value="midtrans" x-model="metode" class="peer sr-only" required>
                                <div class="p-4 rounded-xl border-2 transition-all duration-200 peer-checked:border-primary-500 peer-checked:bg-primary-50/50 bg-white border-dark-200/80 group-hover:border-primary-300">
                                    <div class="flex items-center justify-between">
                                        <div class="font-bold text-dark-900">Otomatis (Midtrans)</div>
                                        <div class="w-5 h-5 rounded-full border-2 border-dark-300 flex items-center justify-center">
                                            <div class="w-2.5 h-2.5 rounded-full bg-primary-600 opacity-0 transition-opacity" :class="metode === 'midtrans' ? 'opacity-100' : ''"></div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-dark-500 mt-1">Transfer Bank (VA), QRIS, E-Wallet.</p>
                                </div>
                            </label>
                            <label class="block relative cursor-pointer group">
                                <input type="radio" name="metode_pembayaran" value="manual" x-model="metode" class="peer sr-only" required>
                                <div class="p-4 rounded-xl border-2 transition-all duration-200 peer-checked:border-primary-500 peer-checked:bg-primary-50/50 bg-white border-dark-200/80 group-hover:border-primary-300">
                                    <div class="flex items-center justify-between">
                                        <div class="font-bold text-dark-900">Transfer Manual</div>
                                        <div class="w-5 h-5 rounded-full border-2 border-dark-300 flex items-center justify-center">
                                            <div class="w-2.5 h-2.5 rounded-full bg-primary-600 opacity-0 transition-opacity" :class="metode === 'manual' ? 'opacity-100' : ''"></div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-dark-500 mt-1">Transfer langsung ke rekening pengelola.</p>
                                </div>
                            </label>
                        </div>

                        <button type="submit" class="btn-primary w-full shadow-lg shadow-primary-500/30" id="btn-confirm">
                            <span id="btn-text">Konfirmasi & Lanjut</span>
                            <span id="btn-loading" class="hidden">
                                <i class="fas fa-spinner fa-spin mr-2"></i> Memproses...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

@if($selectedLapangan && $slots->isNotEmpty())
@push('scripts')
<script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('booking-form');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        const metode = form.querySelector('input[name="metode_pembayaran"]:checked');
        if (!metode || metode.value !== 'midtrans') return; // let manual submit normally

        e.preventDefault();

        const btn = document.getElementById('btn-confirm');
        const btnText = document.getElementById('btn-text');
        const btnLoading = document.getElementById('btn-loading');

        btn.disabled = true;
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': formData.get('_token'),
                'Accept': 'application/json',
            },
            body: formData,
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.snap_token) {
                snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        window.location.href = "{{ url('member/booking') }}/" + data.booking_id + "?transaction_status=" + result.transaction_status + "&order_id=" + result.order_id;
                    },
                    onPending: function(result) {
                        window.location.href = "{{ url('member/booking') }}/" + data.booking_id;
                    },
                    onError: function(result) {
                        alert('Pembayaran gagal! Silakan coba lagi.');
                        window.location.href = "{{ url('member/booking') }}/" + data.booking_id;
                    },
                    onClose: function() {
                        // User closed popup without finishing - redirect to booking detail
                        window.location.href = "{{ url('member/booking') }}/" + data.booking_id;
                    }
                });
            } else {
                alert(data.message || 'Terjadi kesalahan. Silakan coba lagi.');
                btn.disabled = false;
                btnText.classList.remove('hidden');
                btnLoading.classList.add('hidden');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
            btn.disabled = false;
            btnText.classList.remove('hidden');
            btnLoading.classList.add('hidden');
        });
    });
});
</script>
@endpush
@endif
@endsection
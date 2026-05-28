@extends('layouts.app')
@section('title', 'Konfirmasi Booking')

@section('content')
<div class="bg-dark-50 py-12 min-h-[calc(100vh-80px)]">
    <div class="container-custom max-w-4xl">
        <div class="mb-8">
            <h1 class="text-3xl font-display font-black text-dark-900">Konfirmasi Booking</h1>
            <p class="text-dark-500 mt-2">Periksa kembali detail pesanan Anda sebelum melanjutkan ke pembayaran.</p>
        </div>

        @if(!$selectedLapangan || $slots->isEmpty())
            <div class="bg-red-50 text-red-600 p-6 rounded-2xl border border-red-200 text-center">
                <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
                <h3 class="text-xl font-bold mb-2">Data Booking Tidak Valid</h3>
                <p>Silakan kembali dan pilih lapangan serta jam yang tersedia.</p>
                <a href="{{ route('lapangan.index') }}" class="btn-primary mt-6">Kembali ke Daftar Lapangan</a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Form Checkout --}}
                <div class="md:col-span-2 space-y-6">
                    <div class="card p-6 md:p-8">
                        <h3 class="text-lg font-bold text-dark-900 mb-4 border-b border-dark-100 pb-4">Detail Lapangan</h3>
                        <div class="flex gap-4 items-start">
                            <div class="w-24 h-24 rounded-xl overflow-hidden bg-dark-100 flex-none hidden sm:block">
                                @if($selectedLapangan->foto_utama)
                                    <img src="{{ Storage::url($selectedLapangan->foto_utama) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-dark-300"><i class="fas fa-image text-2xl"></i></div>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-dark-900">{{ $selectedLapangan->nama }}</h4>
                                <p class="text-dark-500 text-sm mt-1"><i class="fas fa-layer-group w-5"></i> {{ ucfirst(str_replace('_', ' ', $selectedLapangan->tipe)) }}</p>
                                <p class="text-primary-600 font-bold mt-2">Rp {{ number_format($selectedLapangan->harga_per_jam, 0, ',', '.') }} <span class="text-dark-400 font-normal text-sm">/ jam</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="card p-6 md:p-8">
                        <h3 class="text-lg font-bold text-dark-900 mb-4 border-b border-dark-100 pb-4">Jadwal Terpilih</h3>
                        <div class="flex items-center gap-3 mb-4 text-dark-700 bg-primary-50 p-4 rounded-xl border border-primary-100">
                            <i class="fas fa-calendar-alt text-primary-600 text-xl"></i>
                            <div>
                                <p class="text-xs font-bold text-primary-600 uppercase tracking-wider">Tanggal Main</p>
                                <p class="font-bold text-lg">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($slots as $slot)
                                <div class="bg-dark-50 border border-dark-200 text-dark-700 text-center py-2 px-3 rounded-lg font-medium text-sm">
                                    {{ substr($slot->jam_mulai, 0, 5) }} - {{ substr($slot->jam_selesai, 0, 5) }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Summary & Submit --}}
                <div class="md:col-span-1">
                    <form action="{{ route('member.booking.store') }}" method="POST" class="card p-6 sticky top-24" x-data="{ metode: 'midtrans' }">
                        @csrf
                        <input type="hidden" name="lapangan_id" value="{{ $selectedLapangan->id }}">
                        <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                        @foreach($slots as $slot)
                            <input type="hidden" name="slot_ids[]" value="{{ $slot->id }}">
                        @endforeach

                        <h3 class="text-lg font-bold text-dark-900 mb-4 border-b border-dark-100 pb-4">Ringkasan Biaya</h3>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm text-dark-600">
                                <span>Durasi Main</span>
                                <span>{{ count($slots) }} Jam</span>
                            </div>
                            <div class="flex justify-between text-sm text-dark-600">
                                <span>Harga per Jam</span>
                                <span>Rp {{ number_format($selectedLapangan->harga_per_jam, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between font-black text-lg text-primary-700 pt-3 border-t border-dark-100">
                                <span>Total Tagihan</span>
                                <span>Rp {{ number_format(count($slots) * $selectedLapangan->harga_per_jam, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <h3 class="text-sm font-bold text-dark-900 mb-3">Metode Pembayaran</h3>
                        <div class="space-y-3 mb-6">
                            <label class="block relative cursor-pointer group">
                                <input type="radio" name="metode_pembayaran" value="midtrans" x-model="metode" class="peer sr-only" required>
                                <div class="p-4 rounded-xl border-2 transition-all duration-200 peer-checked:border-primary-500 peer-checked:bg-primary-50 bg-white border-dark-200 hover:border-primary-300">
                                    <div class="flex items-center justify-between">
                                        <div class="font-bold text-dark-900">Otomatis (Midtrans)</div>
                                        <div class="w-5 h-5 rounded-full border-2 border-dark-300 flex items-center justify-center peer-checked:border-primary-600">
                                            <div class="w-2.5 h-2.5 rounded-full bg-primary-600 opacity-0 transition-opacity" :class="metode === 'midtrans' ? 'opacity-100' : ''"></div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-dark-500 mt-1">Transfer Bank (VA), QRIS, E-Wallet.</p>
                                </div>
                            </label>

                            <label class="block relative cursor-pointer group">
                                <input type="radio" name="metode_pembayaran" value="manual" x-model="metode" class="peer sr-only" required>
                                <div class="p-4 rounded-xl border-2 transition-all duration-200 peer-checked:border-primary-500 peer-checked:bg-primary-50 bg-white border-dark-200 hover:border-primary-300">
                                    <div class="flex items-center justify-between">
                                        <div class="font-bold text-dark-900">Transfer Manual</div>
                                        <div class="w-5 h-5 rounded-full border-2 border-dark-300 flex items-center justify-center peer-checked:border-primary-600">
                                            <div class="w-2.5 h-2.5 rounded-full bg-primary-600 opacity-0 transition-opacity" :class="metode === 'manual' ? 'opacity-100' : ''"></div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-dark-500 mt-1">Transfer langsung ke rekening pengelola.</p>
                                </div>
                            </label>
                        </div>

                        <button type="submit" class="btn-primary w-full shadow-lg shadow-primary-500/30">
                            Konfirmasi & Lanjut
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

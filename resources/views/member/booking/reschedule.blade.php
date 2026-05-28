@extends('layouts.app')
@section('title', 'Reschedule Booking')

@section('content')
<div class="bg-dark-50 py-12 min-h-[calc(100vh-80px)]">
    <div class="container-custom max-w-4xl">
        <div class="mb-8">
            <h1 class="text-3xl font-display font-black text-dark-900">Reschedule Jadwal</h1>
            <p class="text-dark-500 mt-2">Ubah jadwal pesanan Anda. Anda hanya dapat memilih durasi yang sama ({{ $booking->durasi_jam }} jam).</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8" x-data="rescheduleWidget()">
            {{-- Form Reschedule --}}
            <div class="md:col-span-2 space-y-6">
                <div class="card p-6 md:p-8">
                    <h3 class="text-lg font-bold text-dark-900 mb-4 border-b border-dark-100 pb-4">Detail Lapangan</h3>
                    <div class="flex gap-4 items-start mb-6">
                        <div class="w-24 h-24 rounded-xl overflow-hidden bg-dark-100 flex-none hidden sm:block">
                            @if($lapangan->foto_utama)
                                <img src="{{ Storage::url($lapangan->foto_utama) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-dark-300"><i class="fas fa-image text-2xl"></i></div>
                            @endif
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-dark-900">{{ $lapangan->nama }}</h4>
                            <p class="text-dark-500 text-sm mt-1"><i class="fas fa-layer-group w-5"></i> {{ ucfirst(str_replace('_', ' ', $lapangan->tipe)) }}</p>
                        </div>
                    </div>

                    <form action="{{ route('member.booking.reschedule', $booking->id) }}" method="GET" id="dateForm">
                        <div class="mb-6">
                            <label class="form-label">Pilih Tanggal Baru</label>
                            <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="document.getElementById('dateForm').submit()" 
                                   min="{{ today()->format('Y-m-d') }}" max="{{ today()->addDays(14)->format('Y-m-d') }}"
                                   class="form-input font-medium text-dark-900" required>
                        </div>
                    </form>
                </div>

                <div class="card p-6 md:p-8">
                    <div class="flex justify-between items-end mb-4 border-b border-dark-100 pb-4">
                        <h3 class="text-lg font-bold text-dark-900">Pilih {{ $booking->durasi_jam }} Slot Jam</h3>
                        <span class="text-sm text-dark-500"><span x-text="selectedSlots.length"></span>/{{ $booking->durasi_jam }} dipilih</span>
                    </div>

                    <form action="{{ route('member.booking.reschedule.store', $booking->id) }}" method="POST" id="rescheduleForm">
                        @csrf
                        <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-6">
                            @forelse($slots as $slot)
                                @if($slot->status === 'available' || ($slot->status === 'reserved' && $booking->tanggal->format('Y-m-d') == $tanggal && $slot->jam_mulai >= $booking->jam_mulai && $slot->jam_selesai <= $booking->jam_selesai))
                                    {{-- Available or currently owned by this booking --}}
                                    <label class="cursor-pointer block">
                                        <input type="checkbox" name="slot_ids[]" value="{{ $slot->id }}" x-model="selectedSlots" class="peer sr-only" @change="checkLimit($event)">
                                        <div class="px-3 py-2.5 text-center text-sm font-medium rounded-xl border-2 transition-all duration-200
                                            peer-checked:bg-primary-50 peer-checked:border-primary-500 peer-checked:text-primary-700
                                            peer-focus-visible:ring-2 peer-focus-visible:ring-primary-500 peer-focus-visible:ring-offset-1
                                            bg-white border-dark-200 text-dark-600 hover:border-dark-300">
                                            <span>{{ substr($slot->jam_mulai, 0, 5) }} - {{ substr($slot->jam_selesai, 0, 5) }}</span>
                                        </div>
                                    </label>
                                @else
                                    <div class="px-3 py-2.5 text-center text-sm font-medium rounded-xl border-2 border-dark-100 bg-dark-50 text-dark-300 cursor-not-allowed opacity-60">
                                        <span>{{ substr($slot->jam_mulai, 0, 5) }} - {{ substr($slot->jam_selesai, 0, 5) }}</span>
                                    </div>
                                @endif
                            @empty
                                <div class="col-span-full text-center py-4 text-sm text-dark-500 bg-red-50 text-red-600 rounded-xl">
                                    Jadwal tidak tersedia untuk tanggal ini.
                                </div>
                            @endforelse
                        </div>
                    </form>
                </div>
            </div>

            {{-- Summary --}}
            <div class="md:col-span-1">
                <div class="card p-6 sticky top-24">
                    <h3 class="text-lg font-bold text-dark-900 mb-4 border-b border-dark-100 pb-4">Ringkasan Biaya</h3>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm text-dark-600">
                            <span>Harga Sewa (Lunas)</span>
                            <span>Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-dark-600 font-bold text-amber-600">
                            <span>Biaya Reschedule (5%)</span>
                            <span>+ Rp {{ number_format($booking->total_harga * 0.05, 0, ',', '.') }}</span>
                        </div>
                        <p class="text-xs text-dark-400 mt-2 italic">Biaya reschedule sebesar 5% dibayarkan langsung di kasir lapangan saat Anda datang bermain.</p>
                    </div>

                    <button type="submit" form="rescheduleForm" class="btn-primary w-full shadow-lg shadow-primary-500/30" 
                            x-bind:disabled="selectedSlots.length !== {{ $booking->durasi_jam }}"
                            :class="{'opacity-50 cursor-not-allowed': selectedSlots.length !== {{ $booking->durasi_jam }}}"
                            onclick="return confirm('Konfirmasi perubahan jadwal?\nAnda akan dikenakan biaya tambahan 5% yang harus dibayar di kasir.')">
                        Konfirmasi Perubahan
                    </button>
                    
                    <a href="{{ route('member.booking.show', $booking->id) }}" class="btn-secondary w-full mt-3 text-center">Batal Ubah</a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('rescheduleWidget', () => ({
        selectedSlots: [],
        maxSlots: {{ $booking->durasi_jam }},
        
        checkLimit(e) {
            if (this.selectedSlots.length > this.maxSlots) {
                // Remove the last selected item
                this.selectedSlots = this.selectedSlots.filter(id => id != e.target.value);
                alert(`Anda hanya dapat memilih maksimal ${this.maxSlots} jam sesuai durasi awal.`);
            }
        }
    }));
});
</script>
@endpush
@endsection

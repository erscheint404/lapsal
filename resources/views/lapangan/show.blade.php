@extends('layouts.app')
@section('title', $lapangan->nama)
@section('content')
{{-- Dark header --}}
<section class="section-dark pt-28 pb-8 relative">
    <div class="absolute inset-0 dot-pattern opacity-15"></div>
    <div class="container-custom relative z-10">
        <nav class="flex text-sm text-dark-400 mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li class="inline-flex items-center">
                    <a href="/" class="hover:text-primary-300 transition-colors"><i class="fas fa-home mr-2"></i>Beranda</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-xs mx-2 text-dark-500"></i>
                        <a href="{{ route('lapangan.index') }}" class="hover:text-primary-300 transition-colors">Lapangan</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-xs mx-2 text-dark-500"></i>
                        <span class="text-white font-semibold line-clamp-1">{{ $lapangan->nama }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
</section>

<div class="bg-dark-50 py-8 -mt-1">
    <div class="container-custom">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Image Gallery --}}
                <div class="card-premium overflow-hidden shadow-lg" x-data="{ mainImage: '{{ $lapangan->foto_utama ? Storage::url($lapangan->foto_utama) : '' }}' }">
                    <div class="aspect-video bg-dark-900 relative">
                        <template x-if="mainImage">
                            <img :src="mainImage" class="w-full h-full object-cover" alt="Foto Lapangan">
                        </template>
                        <template x-if="!mainImage">
                            <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, #1a2740, #0f1b2e);">
                                <i class="fas fa-futbol text-5xl text-dark-600"></i>
                            </div>
                        </template>
                        <div class="absolute top-4 left-4">
                            <span class="inline-flex items-center px-4 py-1.5 rounded-xl text-sm font-bold uppercase tracking-wider shadow-lg" style="background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); color: #263750;">
                                {{ ucfirst(str_replace('_', ' ', $lapangan->tipe)) }}
                            </span>
                        </div>
                    </div>
                    @if($lapangan->fotoLapangan->count() > 0)
                    <div class="p-4 flex gap-4 overflow-x-auto snap-x hide-scrollbar" style="background: rgba(0,0,0,0.02);">
                        @if($lapangan->foto_utama)
                        <button @click="mainImage = '{{ Storage::url($lapangan->foto_utama) }}'"
                                class="flex-none w-24 h-24 rounded-2xl overflow-hidden snap-start focus:outline-none border-2 transition-all duration-200"
                                :class="mainImage === '{{ Storage::url($lapangan->foto_utama) }}' ? 'border-primary-500 ring-2 ring-primary-300/30' : 'border-transparent hover:border-dark-200'">
                            <img src="{{ Storage::url($lapangan->foto_utama) }}" class="w-full h-full object-cover">
                        </button>
                        @endif
                        @foreach($lapangan->fotoLapangan as $foto)
                        <button @click="mainImage = '{{ Storage::url($foto->path) }}'"
                                class="flex-none w-24 h-24 rounded-2xl overflow-hidden snap-start focus:outline-none border-2 transition-all duration-200"
                                :class="mainImage === '{{ Storage::url($foto->path) }}' ? 'border-primary-500 ring-2 ring-primary-300/30' : 'border-transparent hover:border-dark-200'">
                            <img src="{{ Storage::url($foto->path) }}" class="w-full h-full object-cover">
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="card-premium p-7 md:p-8">
                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-6">
                        <div>
                            <h1 class="text-3xl lg:text-4xl font-display font-bold text-dark-900 tracking-tight mb-3">{{ $lapangan->nama }}</h1>
                            <div class="flex items-center gap-4 text-sm flex-wrap">
                                <div class="flex items-center font-bold px-3 py-1.5 rounded-xl" style="background: rgba(204,255,0,0.1); color: #6e8f00;">
                                    <i class="fas fa-star mr-1.5" style="color: #a3cc00;"></i> {{ $lapangan->rata_rating }}
                                    <span class="text-dark-500 font-normal ml-1">({{ $lapangan->ratings->count() }} ulasan)</span>
                                </div>
                                <div class="text-dark-500 flex items-center">
                                    <i class="fas fa-layer-group mr-1.5" style="color: #00b3cc;"></i> {{ ucfirst(str_replace('_', ' ', $lapangan->tipe)) }}
                                </div>
                            </div>
                        </div>
                        <div class="text-left md:text-right px-5 py-4 rounded-2xl" style="background: rgba(204,255,0,0.06); border: 1px solid rgba(204,255,0,0.15);">
                            <p class="text-xs font-bold uppercase tracking-wider mb-1" style="color: #6e8f00;">Harga per Jam</p>
                            <p class="text-2xl lg:text-3xl font-black" style="color: #526b00;">Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-dark-900 mb-3">Deskripsi Lapangan</h3>
                        <p class="text-dark-600 leading-relaxed">{{ $lapangan->deskripsi ?: 'Tidak ada deskripsi.' }}</p>
                    </div>

                    @if($lapangan->fasilitas && count($lapangan->fasilitas) > 0)
                    <div>
                        <h3 class="text-lg font-bold text-dark-900 mb-4">Fasilitas Termasuk</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($lapangan->fasilitas as $fasilitas)
                            <div class="flex items-center gap-3 p-3 rounded-xl border border-dark-100/40 hover:border-primary-300/30 transition-colors" style="background: rgba(204,255,0,0.02);">
                                <i class="fas fa-check-circle" style="color: #a3cc00;"></i>
                                <span class="text-sm font-medium text-dark-700">{{ $fasilitas }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Reviews --}}
                <div class="card-premium p-7 md:p-8" id="ulasan">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-dark-900">Ulasan & Rating</h3>
                        <div class="font-bold text-xl flex items-center px-4 py-1.5 rounded-xl" style="background: rgba(204,255,0,0.1); color: #6e8f00;">
                            <i class="fas fa-star mr-2" style="color: #a3cc00;"></i> {{ $lapangan->rata_rating }}
                        </div>
                    </div>

                    <div class="space-y-6">
                        @forelse($ratings as $rating)
                        <div class="pb-6 last:border-0 last:pb-0" style="border-bottom: 1px solid rgba(0,0,0,0.06);">
                            <div class="flex items-center gap-4 mb-3">
                                <img src="{{ $rating->user->avatar_url }}" alt="" class="w-12 h-12 rounded-full object-cover bg-dark-100 ring-2 ring-dark-100">
                                <div>
                                    <p class="font-bold text-dark-900">{{ $rating->user->name }}</p>
                                    <p class="text-xs text-dark-400">{{ $rating->created_at->translatedFormat('d F Y') }}</p>
                                </div>
                                <div class="ml-auto flex text-sm" style="color: #a3cc00;">
                                    @for($i = 0; $i < $rating->rating; $i++)
                                    <i class="fas fa-star"></i>
                                    @endfor
                                    @for($i = 0; $i < 5 - $rating->rating; $i++)
                                    <i class="far fa-star text-dark-200"></i>
                                    @endfor
                                </div>
                            </div>
                            @if($rating->ulasan)
                            <p class="text-dark-600 p-4 rounded-2xl rounded-tl-none italic text-sm" style="background: rgba(0,0,0,0.02); border: 1px solid rgba(0,0,0,0.04);">"{{ $rating->ulasan }}"</p>
                            @endif
                        </div>
                        @empty
                        <div class="text-center py-8 text-dark-500 rounded-2xl" style="background: rgba(0,0,0,0.02); border: 1px dashed rgba(0,0,0,0.1);">
                            Belum ada ulasan untuk lapangan ini.
                        </div>
                        @endforelse
                        {{ $ratings->fragment('ulasan')->links() }}
                    </div>
                </div>
            </div>

            {{-- Booking Sidebar --}}
            <div class="lg:col-span-1">
                <div class="card-premium p-7 sticky top-28 shadow-xl" style="border: 1px solid rgba(204,255,0,0.08);" x-data="bookingWidget()">
                    <h3 class="text-xl font-bold text-dark-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-calendar-check" style="color: #6e8f00;"></i> Booking Lapangan
                    </h3>
                    <form action="{{ route('member.booking.create') }}" method="GET">
                        <input type="hidden" name="lapangan_id" value="{{ $lapangan->id }}">
                        <div class="mb-6">
                            <label class="form-label">Pilih Tanggal</label>
                            <input type="date" name="tanggal" x-model="tanggal" @change="fetchSlots"
                                   min="{{ today()->format('Y-m-d') }}" max="{{ today()->addDays(14)->format('Y-m-d') }}"
                                   class="form-input font-medium text-dark-900" required>
                        </div>

                        <div class="mb-6">
                            <div class="flex justify-between items-end mb-3">
                                <label class="form-label mb-0">Pilih Jam</label>
                                <span class="text-xs text-dark-400">
                                    <span x-show="loading"><i class="fas fa-spinner fa-spin" style="color: #a3cc00;"></i></span>
                                    <span x-show="!loading" style="color: #a3cc00;"><i class="fas fa-check-circle"></i></span>
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                                <template x-for="slot in slots" :key="slot.id">
                                    <div class="relative">
                                        <template x-if="slot.status === 'available'">
                                            <label class="cursor-pointer block">
                                                <input type="checkbox" name="slot_ids[]" :value="slot.id" x-model="selectedSlots" class="peer sr-only">
                                                <div class="px-3 py-3 text-center text-sm font-medium rounded-2xl border-2 transition-all duration-200
                                                    peer-checked:text-dark-950 peer-checked:border-primary-500
                                                    peer-focus-visible:ring-2 peer-focus-visible:ring-primary-500 peer-focus-visible:ring-offset-1
                                                    bg-white border-dark-200/60 text-dark-600 hover:border-dark-300"
                                                    :style="selectedSlots.includes(String(slot.id)) ? 'background: #ccff00; border-color: #a3cc00; color: #0a1221;' : ''">
                                                    <span x-text="slot.jam_mulai.substring(0,5) + ' - ' + slot.jam_selesai.substring(0,5)"></span>
                                                </div>
                                            </label>
                                        </template>
                                        <template x-if="slot.status !== 'available'">
                                            <div class="px-3 py-3 text-center text-sm font-medium rounded-2xl border-2 border-dark-100/60 bg-dark-50 text-dark-300 cursor-not-allowed opacity-60">
                                                <span x-text="slot.jam_mulai.substring(0,5) + ' - ' + slot.jam_selesai.substring(0,5)"></span>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <div x-show="!loading && slots.length === 0" class="col-span-2 text-center py-4 text-sm rounded-2xl" style="background: rgba(239,68,68,0.05); border: 1px solid rgba(239,68,68,0.15); color: #ef4444;">
                                    <i class="fas fa-exclamation-circle mr-1"></i> Jadwal tidak tersedia untuk tanggal ini.
                                </div>
                            </div>
                        </div>

                        <div x-show="selectedSlots.length > 0" class="mb-6 p-5 rounded-2xl" style="background: rgba(204,255,0,0.06); border: 1px solid rgba(204,255,0,0.12);" x-transition>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-dark-600">Durasi</span>
                                <span class="font-bold text-dark-900"><span x-text="selectedSlots.length"></span> Jam</span>
                            </div>
                            <div class="flex justify-between items-center pt-3" style="border-top: 1px solid rgba(204,255,0,0.15);">
                                <span class="text-sm font-bold text-dark-900">Total</span>
                                <span class="font-black text-lg" style="color: #526b00;">Rp <span x-text="formatRupiah(selectedSlots.length * {{ $lapangan->harga_per_jam }})"></span></span>
                            </div>
                        </div>

                        @auth
                            <button type="submit" class="btn-primary w-full py-3.5" :disabled="selectedSlots.length === 0" :class="{'opacity-50 cursor-not-allowed': selectedSlots.length === 0}">
                                Lanjut Booking <i class="fas fa-arrow-right ml-1.5"></i>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="btn-primary w-full flex justify-center py-3.5">
                                <i class="fas fa-sign-in-alt mr-2"></i> Login untuk Booking
                            </a>
                        @endauth
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('bookingWidget', () => ({
        tanggal: '{{ $tanggal }}',
        lapanganId: {{ $lapangan->id }},
        slots: [],
        selectedSlots: [],
        loading: false,

        init() {
            this.fetchSlots();
        },

        fetchSlots() {
            if (!this.tanggal) return;
            this.loading = true;
            this.selectedSlots = [];
            fetch(`/api/lapangan/${this.lapanganId}/slots/${this.tanggal}`)
                .then(res => res.json())
                .then(data => {
                    this.slots = data;
                })
                .catch(err => console.error(err))
                .finally(() => {
                    this.loading = false;
                });
        },

        formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }
    }));
});
</script>
@endpush
@endsection
@extends('layouts.app')
@section('title', $lapangan->nama)
@section('content')
<div class="bg-dark-50 py-8">
    <div class="container-custom">
        {{-- Breadcrumb --}}
        <nav class="flex text-sm text-dark-500 mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li class="inline-flex items-center">
                    <a href="/" class="hover:text-primary-600 transition-colors"><i class="fas fa-home mr-2"></i>Beranda</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-xs mx-2"></i>
                        <a href="{{ route('lapangan.index') }}" class="hover:text-primary-600 transition-colors">Lapangan</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-xs mx-2"></i>
                        <span class="text-dark-900 font-medium line-clamp-1">{{ $lapangan->nama }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Col: Images & Info --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Gallery --}}
                <div class="card overflow-hidden" x-data="{ mainImage: '{{ $lapangan->foto_utama ? Storage::url($lapangan->foto_utama) : '' }}' }">
                    <div class="aspect-video bg-dark-100 relative">
                        <template x-if="mainImage">
                            <img :src="mainImage" class="w-full h-full object-cover" alt="Foto Lapangan">
                        </template>
                        <template x-if="!mainImage">
                            <div class="w-full h-full flex items-center justify-center text-dark-300">
                                <i class="fas fa-image text-5xl"></i>
                            </div>
                        </template>
                        <div class="absolute top-4 left-4">
                            <span class="badge badge-success shadow-lg backdrop-blur-md bg-white/90 text-sm px-3 py-1.5">
                                {{ ucfirst(str_replace('_', ' ', $lapangan->tipe)) }}
                            </span>
                        </div>
                    </div>
                    
                    @if($lapangan->fotoLapangan->count() > 0)
                    <div class="p-4 flex gap-4 overflow-x-auto snap-x hide-scrollbar">
                        @if($lapangan->foto_utama)
                        <button @click="mainImage = '{{ Storage::url($lapangan->foto_utama) }}'" 
                                class="flex-none w-24 h-24 rounded-xl overflow-hidden snap-start focus:outline-none focus:ring-2 focus:ring-primary-500 border-2"
                                :class="mainImage === '{{ Storage::url($lapangan->foto_utama) }}' ? 'border-primary-500' : 'border-transparent'">
                            <img src="{{ Storage::url($lapangan->foto_utama) }}" class="w-full h-full object-cover">
                        </button>
                        @endif
                        
                        @foreach($lapangan->fotoLapangan as $foto)
                        <button @click="mainImage = '{{ Storage::url($foto->path) }}'" 
                                class="flex-none w-24 h-24 rounded-xl overflow-hidden snap-start focus:outline-none focus:ring-2 focus:ring-primary-500 border-2"
                                :class="mainImage === '{{ Storage::url($foto->path) }}' ? 'border-primary-500' : 'border-transparent'">
                            <img src="{{ Storage::url($foto->path) }}" class="w-full h-full object-cover">
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Detail --}}
                <div class="card p-6 md:p-8">
                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-6">
                        <div>
                            <h1 class="text-3xl font-display font-bold text-dark-900 mb-2">{{ $lapangan->nama }}</h1>
                            <div class="flex items-center gap-4 text-sm">
                                <div class="flex items-center text-amber-500 font-bold bg-amber-50 px-2.5 py-1 rounded-lg">
                                    <i class="fas fa-star mr-1"></i> {{ $lapangan->rata_rating }}
                                    <span class="text-dark-500 font-normal ml-1">({{ $lapangan->ratings->count() }} ulasan)</span>
                                </div>
                                <div class="text-dark-500">
                                    <i class="fas fa-layer-group mr-1"></i> {{ ucfirst(str_replace('_', ' ', $lapangan->tipe)) }}
                                </div>
                            </div>
                        </div>
                        <div class="text-left md:text-right bg-primary-50 px-4 py-3 rounded-2xl border border-primary-100">
                            <p class="text-xs text-primary-600 font-bold uppercase tracking-wider mb-1">Harga per Jam</p>
                            <p class="text-2xl font-black text-primary-700">Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="prose prose-emerald max-w-none mb-8">
                        <h3 class="text-lg font-bold text-dark-900 mb-3">Deskripsi Lapangan</h3>
                        <p class="text-dark-600 leading-relaxed">{{ $lapangan->deskripsi ?: 'Tidak ada deskripsi.' }}</p>
                    </div>

                    @if($lapangan->fasilitas && count($lapangan->fasilitas) > 0)
                    <div>
                        <h3 class="text-lg font-bold text-dark-900 mb-4">Fasilitas Termasuk</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($lapangan->fasilitas as $fasilitas)
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-dark-50 border border-dark-100">
                                <i class="fas fa-check-circle text-primary-500"></i>
                                <span class="text-sm font-medium text-dark-700">{{ $fasilitas }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Ulasan --}}
                <div class="card p-6 md:p-8" id="ulasan">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-dark-900">Ulasan & Rating</h3>
                        <div class="text-amber-500 font-bold text-xl flex items-center">
                            <i class="fas fa-star mr-2"></i> {{ $lapangan->rata_rating }}
                        </div>
                    </div>

                    <div class="space-y-6">
                        @forelse($ratings as $rating)
                        <div class="border-b border-dark-100 pb-6 last:border-0 last:pb-0">
                            <div class="flex items-center gap-4 mb-3">
                                <img src="{{ $rating->user->avatar_url }}" alt="" class="w-12 h-12 rounded-full object-cover bg-dark-100">
                                <div>
                                    <p class="font-bold text-dark-900">{{ $rating->user->name }}</p>
                                    <p class="text-xs text-dark-400">{{ $rating->created_at->translatedFormat('d F Y') }}</p>
                                </div>
                                <div class="ml-auto flex text-amber-400 text-sm">
                                    @for($i = 0; $i < $rating->rating; $i++)
                                    <i class="fas fa-star"></i>
                                    @endfor
                                    @for($i = 0; $i < 5 - $rating->rating; $i++)
                                    <i class="far fa-star text-dark-200"></i>
                                    @endfor
                                </div>
                            </div>
                            @if($rating->ulasan)
                            <p class="text-dark-600 bg-dark-50 p-4 rounded-xl rounded-tl-none italic text-sm">"{{ $rating->ulasan }}"</p>
                            @endif
                        </div>
                        @empty
                        <div class="text-center py-8 text-dark-500 bg-dark-50 rounded-2xl border border-dashed border-dark-200">
                            Belum ada ulasan untuk lapangan ini.
                        </div>
                        @endforelse

                        {{ $ratings->fragment('ulasan')->links() }}
                    </div>
                </div>
            </div>

            {{-- Right Col: Booking Widget --}}
            <div class="lg:col-span-1">
                <div class="card p-6 sticky top-24" x-data="bookingWidget()">
                    <h3 class="text-xl font-bold text-dark-900 mb-6">Booking Lapangan</h3>
                    
                    <form action="{{ route('member.booking.create') }}" method="GET">
                        <input type="hidden" name="lapangan_id" value="{{ $lapangan->id }}">
                        
                        {{-- Date Picker --}}
                        <div class="mb-6">
                            <label class="form-label">Pilih Tanggal</label>
                            <input type="date" name="tanggal" x-model="tanggal" @change="fetchSlots" 
                                   min="{{ today()->format('Y-m-d') }}" max="{{ today()->addDays(14)->format('Y-m-d') }}"
                                   class="form-input font-medium text-dark-900" required>
                        </div>

                        {{-- Slots --}}
                        <div class="mb-6">
                            <div class="flex justify-between items-end mb-3">
                                <label class="form-label mb-0">Pilih Jam</label>
                                <span class="text-xs text-dark-400">Loading: <span x-show="loading"><i class="fas fa-spinner fa-spin"></i></span><span x-show="!loading" class="text-primary-600"><i class="fas fa-check"></i></span></span>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-2 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                                <template x-for="slot in slots" :key="slot.id">
                                    <div class="relative">
                                        <template x-if="slot.status === 'available'">
                                            <label class="cursor-pointer block">
                                                <input type="checkbox" name="slot_ids[]" :value="slot.id" x-model="selectedSlots" class="peer sr-only">
                                                <div class="px-3 py-2.5 text-center text-sm font-medium rounded-xl border-2 transition-all duration-200
                                                    peer-checked:bg-primary-50 peer-checked:border-primary-500 peer-checked:text-primary-700
                                                    peer-focus-visible:ring-2 peer-focus-visible:ring-primary-500 peer-focus-visible:ring-offset-1
                                                    bg-white border-dark-200 text-dark-600 hover:border-dark-300">
                                                    <span x-text="slot.jam_mulai + ' - ' + slot.jam_selesai"></span>
                                                </div>
                                            </label>
                                        </template>
                                        <template x-if="slot.status !== 'available'">
                                            <div class="px-3 py-2.5 text-center text-sm font-medium rounded-xl border-2 border-dark-100 bg-dark-50 text-dark-300 cursor-not-allowed opacity-60">
                                                <span x-text="slot.jam_mulai + ' - ' + slot.jam_selesai"></span>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                
                                <div x-show="!loading && slots.length === 0" class="col-span-2 text-center py-4 text-sm text-dark-500 bg-red-50 text-red-600 rounded-xl">
                                    Jadwal tidak tersedia untuk tanggal ini.
                                </div>
                            </div>
                        </div>

                        {{-- Summary --}}
                        <div x-show="selectedSlots.length > 0" class="mb-6 p-4 bg-primary-50 rounded-xl border border-primary-100" x-transition>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-dark-600">Durasi</span>
                                <span class="font-bold text-dark-900"><span x-text="selectedSlots.length"></span> Jam</span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-primary-200/50">
                                <span class="text-sm font-bold text-dark-900">Total</span>
                                <span class="font-black text-lg text-primary-700">Rp <span x-text="formatRupiah(selectedSlots.length * {{ $lapangan->harga_per_jam }})"></span></span>
                            </div>
                        </div>

                        @auth
                            <button type="submit" class="btn-primary w-full shadow-lg shadow-primary-500/30" :disabled="selectedSlots.length === 0" :class="{'opacity-50 cursor-not-allowed': selectedSlots.length === 0}">
                                Lanjut Booking
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="btn-primary w-full flex justify-center shadow-lg shadow-primary-500/30">
                                Login untuk Booking
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

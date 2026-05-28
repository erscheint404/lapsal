@extends('layouts.app')

@section('content')
{{-- Hero Section --}}
<section class="relative pt-24 pb-32 lg:pt-36 lg:pb-40 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 to-teal-50 -z-10"></div>
    <div class="absolute inset-y-0 right-0 w-1/2 bg-gradient-to-l from-emerald-100/50 to-transparent -z-10"></div>
    
    <div class="container-custom relative">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="max-w-2xl" x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-100 text-emerald-800 font-semibold text-sm mb-6 transition-all duration-700 transform"
                     :class="show ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                    </span>
                    {{ $slotTersedia }} Slot Tersedia Hari Ini
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-display font-black text-dark-900 leading-tight mb-6 transition-all duration-700 delay-100 transform"
                    :class="show ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    Main Futsal Makin <span class="text-transparent bg-clip-text bg-gradient-primary">Gampang & Cepat</span>
                </h1>
                
                <p class="text-lg text-dark-500 mb-8 leading-relaxed transition-all duration-700 delay-200 transform"
                   :class="show ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    Cek jadwal, booking lapangan, dan bayar secara online dalam hitungan menit. Jangan biarkan tim lawan mengambil jadwalmu!
                </p>
                
                <div class="flex flex-wrap gap-4 transition-all duration-700 delay-300 transform"
                     :class="show ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    <a href="{{ route('lapangan.index') }}" class="btn-primary text-lg px-8 py-4">
                        <i class="fas fa-search mr-2"></i> Cari Lapangan
                    </a>
                    <a href="#cara-kerja" class="px-8 py-4 rounded-xl font-bold text-dark-700 bg-white border-2 border-dark-200 hover:border-primary-500 hover:text-primary-600 transition-all duration-300">
                        Cara Kerja
                    </a>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-6 mt-12 pt-8 border-t border-dark-200 transition-all duration-700 delay-400 transform"
                     :class="show ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    <div>
                        <p class="text-3xl font-display font-black text-dark-900 mb-1">{{ $totalMember }}+</p>
                        <p class="text-sm font-medium text-dark-500">Member Aktif</p>
                    </div>
                    <div>
                        <p class="text-3xl font-display font-black text-dark-900 mb-1">{{ $totalBooking }}+</p>
                        <p class="text-sm font-medium text-dark-500">Total Booking</p>
                    </div>
                    <div>
                        <p class="text-3xl font-display font-black text-dark-900 mb-1 flex items-center">
                            {{ $rataRating }} <i class="fas fa-star text-amber-400 text-xl ml-1"></i>
                        </p>
                        <p class="text-sm font-medium text-dark-500">Rating Rata-rata</p>
                    </div>
                </div>
            </div>
            
            {{-- Hero Image / Abstract --}}
            <div class="hidden lg:block relative h-[600px]" x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)">
                <div class="absolute inset-0 bg-gradient-to-tr from-emerald-500 to-teal-400 rounded-[3rem] transform rotate-3 scale-105 transition-all duration-1000"
                     :class="show ? 'opacity-20 translate-y-0' : 'opacity-0 translate-y-12'"></div>
                <div class="absolute inset-0 bg-dark-900 rounded-[3rem] shadow-2xl overflow-hidden transition-all duration-1000 delay-100"
                     :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">
                    <img src="https://images.unsplash.com/photo-1536122985607-4fea00b12204?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                         alt="Futsal Court" class="w-full h-full object-cover opacity-60 mix-blend-overlay">
                    <div class="absolute inset-0 bg-gradient-to-t from-dark-900 via-dark-900/40 to-transparent"></div>
                    
                    {{-- Floating Elements --}}
                    <div class="absolute bottom-10 left-10 right-10 p-6 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 text-white">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-full bg-emerald-500 flex items-center justify-center">
                                <i class="fas fa-check text-xl"></i>
                            </div>
                            <div>
                                <p class="font-bold">Booking Terkonfirmasi</p>
                                <p class="text-sm text-white/70">Lapangan Vinyl A - 19:00 WIB</p>
                            </div>
                        </div>
                        <div class="w-full bg-white/20 h-2 rounded-full overflow-hidden">
                            <div class="bg-emerald-400 h-full w-full relative overflow-hidden">
                                <div class="absolute inset-0 bg-white/30 -skew-x-12 animate-[shimmer_2s_infinite]"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Lapangan Terpopuler --}}
<section class="py-24 bg-white">
    <div class="container-custom">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div class="max-w-2xl">
                <h2 class="text-3xl md:text-4xl font-display font-bold text-dark-900 mb-4">Pilihan Lapangan</h2>
                <p class="text-dark-500 text-lg">Berbagai jenis lapangan futsal dengan standar internasional menanti Anda.</p>
            </div>
            <a href="{{ route('lapangan.index') }}" class="btn-secondary whitespace-nowrap">
                Lihat Semua Lapangan <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($lapangan as $l)
            <a href="{{ route('lapangan.show', $l->id) }}" class="group block h-full">
                <div class="card h-full overflow-hidden flex flex-col transition-all duration-300 hover:shadow-2xl hover:shadow-emerald-500/20 hover:-translate-y-2">
                    <div class="relative h-48 overflow-hidden">
                        @if($l->foto_utama)
                        <img src="{{ Storage::url($l->foto_utama) }}" alt="{{ $l->nama }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @else
                        <div class="w-full h-full bg-dark-100 flex items-center justify-center">
                            <i class="fas fa-image text-4xl text-dark-300"></i>
                        </div>
                        @endif
                        <div class="absolute top-4 left-4">
                            <span class="badge badge-success shadow-lg">
                                {{ ucfirst(str_replace('_', ' ', $l->tipe)) }}
                            </span>
                        </div>
                    </div>
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <h3 class="font-display font-bold text-lg text-dark-900 line-clamp-1">{{ $l->nama }}</h3>
                            <div class="flex items-center gap-1 text-sm font-medium bg-amber-50 text-amber-700 px-2 py-1 rounded-lg">
                                <i class="fas fa-star text-amber-500"></i>
                                <span>{{ $l->rata_rating }}</span>
                            </div>
                        </div>
                        <p class="text-sm text-dark-500 line-clamp-2 mb-4">{{ $l->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                        
                        <div class="mt-auto pt-4 border-t border-dark-100 flex items-center justify-between">
                            <div>
                                <p class="text-xs text-dark-400 font-medium uppercase tracking-wider mb-1">Harga per Jam</p>
                                <p class="font-bold text-primary-600">Rp {{ number_format($l->harga_per_jam, 0, ',', '.') }}</p>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center group-hover:bg-primary-600 group-hover:text-white transition-colors">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full py-12 text-center text-dark-500">
                Belum ada lapangan yang aktif.
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- Cara Kerja --}}
<section id="cara-kerja" class="py-24 bg-dark-50">
    <div class="container-custom">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="text-3xl md:text-4xl font-display font-bold text-dark-900 mb-4">Cara Booking Mudah</h2>
            <p class="text-dark-500 text-lg">Hanya butuh 4 langkah sederhana untuk mengamankan jadwal main futsalmu.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
            <div class="hidden md:block absolute top-12 left-1/8 right-1/8 h-0.5 bg-dark-200 border-dashed border-t-2 border-dark-200 z-0"></div>
            
            <div class="relative z-10 text-center group">
                <div class="w-24 h-24 mx-auto bg-white rounded-3xl shadow-xl shadow-dark-200/50 flex items-center justify-center text-3xl text-emerald-500 mb-6 border border-dark-100 group-hover:-translate-y-2 group-hover:border-emerald-500 group-hover:text-emerald-600 transition-all duration-300">
                    <i class="fas fa-search"></i>
                </div>
                <h4 class="font-bold text-xl text-dark-900 mb-2">1. Pilih Lapangan</h4>
                <p class="text-dark-500 text-sm">Cari dan pilih lapangan yang sesuai dengan kebutuhan timmu.</p>
            </div>
            
            <div class="relative z-10 text-center group">
                <div class="w-24 h-24 mx-auto bg-white rounded-3xl shadow-xl shadow-dark-200/50 flex items-center justify-center text-3xl text-emerald-500 mb-6 border border-dark-100 group-hover:-translate-y-2 group-hover:border-emerald-500 group-hover:text-emerald-600 transition-all duration-300">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h4 class="font-bold text-xl text-dark-900 mb-2">2. Pilih Jadwal</h4>
                <p class="text-dark-500 text-sm">Tentukan tanggal dan pilih slot waktu yang masih tersedia.</p>
            </div>
            
            <div class="relative z-10 text-center group">
                <div class="w-24 h-24 mx-auto bg-white rounded-3xl shadow-xl shadow-dark-200/50 flex items-center justify-center text-3xl text-emerald-500 mb-6 border border-dark-100 group-hover:-translate-y-2 group-hover:border-emerald-500 group-hover:text-emerald-600 transition-all duration-300">
                    <i class="fas fa-credit-card"></i>
                </div>
                <h4 class="font-bold text-xl text-dark-900 mb-2">3. Bayar</h4>
                <p class="text-dark-500 text-sm">Selesaikan pembayaran via transfer bank, e-wallet, atau manual.</p>
            </div>
            
            <div class="relative z-10 text-center group">
                <div class="w-24 h-24 mx-auto bg-white rounded-3xl shadow-xl shadow-dark-200/50 flex items-center justify-center text-3xl text-emerald-500 mb-6 border border-dark-100 group-hover:-translate-y-2 group-hover:border-emerald-500 group-hover:text-emerald-600 transition-all duration-300">
                    <i class="fas fa-qrcode"></i>
                </div>
                <h4 class="font-bold text-xl text-dark-900 mb-2">4. Main!</h4>
                <p class="text-dark-500 text-sm">Tunjukkan QR Code booking kepada petugas di lokasi lapangan.</p>
            </div>
        </div>
    </div>
</section>

{{-- Leaderboard Preview --}}
<section class="py-24 bg-dark-900 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20"></div>
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-emerald-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-50"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-teal-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-50"></div>
    
    <div class="container-custom relative z-10">
        <div class="flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="md:w-1/2 max-w-xl">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-white/10 border border-white/20 text-emerald-400 font-semibold text-sm mb-6">
                    <i class="fas fa-fire"></i> Hall of Fame
                </div>
                <h2 class="text-3xl md:text-5xl font-display font-bold mb-6">Jadilah Pencetak Gol Terbanyak!</h2>
                <p class="text-dark-300 text-lg mb-8 leading-relaxed">
                    Setiap gol yang kamu cetak dalam sesi booking akan tercatat di sistem kami. Bersainglah dengan pemain lain dan raih posisi teratas di Leaderboard Lapsal.
                </p>
                <a href="{{ route('leaderboard.index') }}" class="btn-primary">
                    Lihat Leaderboard Penuh <i class="fas fa-trophy ml-2"></i>
                </a>
            </div>
            
            <div class="md:w-1/2 w-full">
                <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-3xl p-1 shadow-2xl">
                    <div class="bg-dark-950/80 rounded-[1.35rem] p-6">
                        <div class="flex items-center justify-between mb-6 pb-6 border-b border-white/10">
                            <h3 class="font-bold text-xl">Top 5 Scorer</h3>
                            <i class="fas fa-medal text-amber-400 text-2xl"></i>
                        </div>
                        
                        <div class="space-y-4">
                            @forelse($leaderboard as $index => $l)
                            <div class="flex items-center gap-4 p-3 rounded-xl {{ $index === 0 ? 'bg-amber-500/10 border border-amber-500/30' : 'bg-white/5 hover:bg-white/10' }} transition-colors">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold {{ $index === 0 ? 'bg-amber-500 text-dark-900' : ($index === 1 ? 'bg-dark-300 text-dark-900' : ($index === 2 ? 'bg-amber-700 text-white' : 'bg-dark-800 text-dark-400')) }}">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1">
                                    <p class="font-bold {{ $index === 0 ? 'text-amber-400' : 'text-white' }}">{{ $l->nama_pemain }}</p>
                                    <p class="text-xs text-dark-400">{{ $l->total_sesi }} Sesi Bermain</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-display font-black text-2xl {{ $index === 0 ? 'text-amber-400' : 'text-white' }}">{{ $l->total_gol }}</p>
                                    <p class="text-[10px] uppercase tracking-wider text-dark-400 font-bold">GOL</p>
                                </div>
                            </div>
                            @empty
                            <p class="text-center text-dark-400 py-4">Belum ada data statistik gol.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Testimoni --}}
@if($testimoni->count() > 0)
<section class="py-24 bg-white">
    <div class="container-custom">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="text-3xl md:text-4xl font-display font-bold text-dark-900 mb-4">Apa Kata Mereka?</h2>
            <p class="text-dark-500 text-lg">Pengalaman bermain dari para member Lapsal Futsal.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($testimoni as $t)
            <div class="card p-6 flex flex-col h-full hover:-translate-y-2 transition-transform duration-300">
                <div class="flex items-center gap-1 mb-4 text-amber-400">
                    @for($i = 0; $i < $t->rating; $i++)
                    <i class="fas fa-star"></i>
                    @endfor
                    @for($i = 0; $i < 5 - $t->rating; $i++)
                    <i class="far fa-star text-dark-200"></i>
                    @endfor
                </div>
                <p class="text-dark-700 italic flex-1 mb-6">"{!! nl2br(e($t->ulasan ?? 'Mantap lapangannya!')) !!}"</p>
                
                <div class="flex items-center gap-4 pt-4 border-t border-dark-100">
                    <img src="{{ $t->user->avatar_url }}" alt="" class="w-10 h-10 rounded-full object-cover">
                    <div>
                        <p class="font-bold text-dark-900 text-sm">{{ $t->user->name }}</p>
                        <p class="text-xs text-dark-400">Main di {{ $t->lapangan->nama }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

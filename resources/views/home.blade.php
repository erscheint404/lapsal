@extends('layouts.app')

@section('content')
{{-- ============================================
     HERO SECTION — Full-screen dark hero
     ============================================ --}}
<section class="relative min-h-screen flex items-center overflow-hidden bg-mesh-dark">
    {{-- Animated decorative elements --}}
    <div class="absolute inset-0 dot-pattern opacity-30"></div>
    <div class="absolute top-20 left-[10%] w-2 h-2 rounded-full animate-float" style="background: #ccff00; box-shadow: 0 0 20px rgba(204,255,0,0.4); animation-delay: 0s;"></div>
    <div class="absolute top-40 right-[15%] w-1.5 h-1.5 rounded-full animate-float" style="background: #00e5ff; box-shadow: 0 0 15px rgba(0,229,255,0.4); animation-delay: 1s;"></div>
    <div class="absolute bottom-32 left-[20%] w-1 h-1 rounded-full animate-float" style="background: #ccff00; box-shadow: 0 0 10px rgba(204,255,0,0.3); animation-delay: 2s;"></div>
    <div class="absolute top-[60%] right-[8%] w-1.5 h-1.5 rounded-full animate-float-slow" style="background: #00e5ff; box-shadow: 0 0 15px rgba(0,229,255,0.3); animation-delay: 0.5s;"></div>
    <div class="absolute bottom-[20%] right-[30%] w-1 h-1 rounded-full animate-float" style="background: #ccff00; box-shadow: 0 0 10px rgba(204,255,0,0.3); animation-delay: 3s;"></div>

    {{-- Large glow orbs --}}
    <div class="absolute -top-40 -left-40 w-[500px] h-[500px] rounded-full blur-[180px]" style="background: rgba(204,255,0,0.06);"></div>
    <div class="absolute -bottom-40 -right-40 w-[500px] h-[500px] rounded-full blur-[180px]" style="background: rgba(0,229,255,0.05);"></div>

    <div class="container-custom relative z-10 pt-32 pb-20">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            {{-- Text Content --}}
            <div class="max-w-2xl" x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">
                {{-- Live badge --}}
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full font-bold text-sm mb-8 transition-all duration-700 transform"
                     style="background: rgba(204,255,0,0.1); border: 1px solid rgba(204,255,0,0.2); color: #ccff00;"
                     :class="show ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background: #ccff00;"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5" style="background: #ccff00;"></span>
                    </span>
                    {{ $slotTersedia }} Slot Tersedia Hari Ini
                </div>

                <h1 class="text-5xl md:text-6xl lg:text-7xl font-display font-black text-white leading-[1.05] mb-8 transition-all duration-700 delay-100 transform tracking-tight"
                    :class="show ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    Main Futsal<br>Makin
                    <span class="text-gradient-premium">Gampang</span>
                </h1>

                <p class="text-lg md:text-xl text-dark-300 mb-10 leading-relaxed max-w-xl transition-all duration-700 delay-200 transform"
                   :class="show ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    Cek jadwal, booking lapangan, dan bayar secara online dalam hitungan menit. Jangan biarkan tim lawan mengambil jadwalmu!
                </p>

                <div class="flex flex-wrap gap-4 transition-all duration-700 delay-300 transform"
                     :class="show ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    <a href="{{ route('lapangan.index') }}" class="btn-primary text-lg px-10 py-5">
                        <i class="fas fa-search mr-2"></i> Cari Lapangan
                    </a>
                    <a href="#cara-kerja" class="btn-outline-light text-lg px-10 py-5">
                        Cara Kerja
                    </a>
                </div>

                {{-- Stats strip --}}
                <div class="grid grid-cols-3 gap-8 mt-14 pt-10 transition-all duration-700 delay-400 transform"
                     style="border-top: 1px solid rgba(255,255,255,0.08);"
                     :class="show ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    <div>
                        <p class="text-4xl font-display font-black text-white mb-1.5" data-count="{{ $totalMember }}">0</p>
                        <p class="text-sm font-semibold text-dark-400">Member Aktif</p>
                    </div>
                    <div>
                        <p class="text-4xl font-display font-black text-white mb-1.5" data-count="{{ $totalBooking }}">0</p>
                        <p class="text-sm font-semibold text-dark-400">Total Booking</p>
                    </div>
                    <div>
                        <p class="text-4xl font-display font-black text-white mb-1.5 flex items-center">
                            {{ $rataRating }} <i class="fas fa-star ml-2 text-2xl" style="color: #ccff00;"></i>
                        </p>
                        <p class="text-sm font-semibold text-dark-400">Rating Rata-rata</p>
                    </div>
                </div>
            </div>

            {{-- Hero Image --}}
            <div class="hidden lg:block relative h-[620px]" x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)">
                <div class="absolute -inset-4 rounded-[3.5rem] transform rotate-3 blur-xl transition-all duration-1000"
                     style="background: linear-gradient(135deg, rgba(204,255,0,0.15) 0%, rgba(0,229,255,0.15) 100%);"
                     :class="show ? 'scale-100 opacity-100' : 'scale-95 opacity-0'"></div>
                <div class="relative h-full rounded-[3rem] overflow-hidden shadow-2xl transition-all duration-1000 delay-100"
                     style="border: 1px solid rgba(255,255,255,0.1);"
                     :class="show ? 'opacity-100 translate-y-0 scale-100' : 'opacity-0 translate-y-12 scale-95'">
                    <img src="{{ asset('images/hero_futsal_court.png') }}"
                         alt="Futsal Court" class="w-full h-full object-cover">
                    <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(10,18,33,0.9) 0%, rgba(10,18,33,0.3) 40%, transparent 100%);"></div>

                    {{-- Floating booking card --}}
                    <div class="absolute bottom-8 left-8 right-8 p-6 rounded-2xl" style="background: rgba(255,255,255,0.06); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1);">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #ccff00, #a3cc00);">
                                <i class="fas fa-check text-dark-950 text-lg"></i>
                            </div>
                            <div class="text-white">
                                <p class="font-bold text-lg">Booking Dikonfirmasi</p>
                                <p class="text-sm text-white/60">Lapangan Vinyl A — 19:00 WIB</p>
                            </div>
                        </div>
                        <div class="w-full h-2 rounded-full overflow-hidden" style="background: rgba(255,255,255,0.1);">
                            <div class="h-full w-3/4 rounded-full relative overflow-hidden" style="background: #ccff00;">
                                <div class="absolute inset-0 animate-shimmer"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Floating rating badge --}}
                <div class="absolute top-20 -right-4 w-24 h-24 rounded-3xl flex items-center justify-center animate-float shadow-xl"
                     style="background: rgba(10,18,33,0.8); backdrop-filter: blur(20px); border: 1px solid rgba(204,255,0,0.2); animation-delay: 0.5s;">
                    <div class="text-center">
                        <i class="fas fa-star text-2xl" style="color: #ccff00;"></i>
                        <p class="text-sm font-black text-white mt-1">4.9/5.0</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom gradient fade --}}
    <div class="absolute bottom-0 left-0 right-0 h-32" style="background: linear-gradient(to top, #ffffff, transparent);"></div>
</section>

{{-- ============================================
     LAPANGAN TERPOPULER
     ============================================ --}}
<section class="py-24 bg-white relative">
    <div class="container-custom">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-14 reveal">
            <div class="max-w-2xl">
                <span class="text-sm font-bold uppercase tracking-widest mb-3 block" style="color: #8ab300;">Pilihan Terbaik</span>
                <h2 class="text-4xl md:text-5xl font-display font-bold text-dark-900 tracking-tight mb-4">Pilihan Lapangan</h2>
                <p class="text-dark-500 text-lg leading-relaxed">Berbagai jenis lapangan futsal dengan standar internasional menanti Anda.</p>
            </div>
            <a href="{{ route('lapangan.index') }}" class="btn-secondary whitespace-nowrap">
                Lihat Semua <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($lapangan as $l)
            <a href="{{ route('lapangan.show', $l->id) }}" class="group block h-full reveal" style="transition-delay: {{ $loop->index * 100 }}ms;">
                <div class="bg-white rounded-3xl border border-dark-100/40 overflow-hidden flex flex-col transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 h-full"
                     style="hover-border-color: rgba(204,255,0,0.3);">
                    <div class="relative h-52 overflow-hidden">
                        @if($l->foto_utama)
                        <img src="{{ Storage::url($l->foto_utama) }}" alt="{{ $l->nama }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        @else
                        <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, #1a2740, #0f1b2e);">
                            <i class="fas fa-futbol text-4xl text-dark-600"></i>
                        </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-dark-950/40 to-transparent"></div>
                        <div class="absolute top-4 left-4">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm"
                                  style="background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); color: #263750;">
                                {{ ucfirst(str_replace('_', ' ', $l->tipe)) }}
                            </span>
                        </div>
                        @if($l->diskon)
                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center px-2.5 py-1.5 rounded-xl text-dark-950 text-xs font-bold shadow-lg" style="background: #ccff00;">DISKON</span>
                        </div>
                        @endif
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <h3 class="font-display font-bold text-xl text-dark-900 line-clamp-1">{{ $l->nama }}</h3>
                            <div class="flex items-center gap-1 text-sm font-bold px-2.5 py-1 rounded-xl shrink-0" style="background: rgba(204,255,0,0.1); color: #6e8f00;">
                                <i class="fas fa-star text-xs" style="color: #a3cc00;"></i>
                                <span>{{ $l->rata_rating }}</span>
                            </div>
                        </div>
                        <p class="text-sm text-dark-500 line-clamp-2 mb-4 leading-relaxed">{{ $l->deskripsi ?? 'Tidak ada deskripsi' }}</p>

                        <div class="mt-auto pt-5 flex items-center justify-between" style="border-top: 1px solid rgba(0,0,0,0.06);">
                            <div>
                                <p class="text-[10px] text-dark-400 font-bold uppercase tracking-widest mb-1">Harga per Jam</p>
                                <p class="font-black text-2xl font-display tracking-tight" style="color: #6e8f00;">Rp {{ number_format($l->harga_per_jam, 0, ',', '.') }}</p>
                            </div>
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-300 group-hover:scale-110"
                                 style="background: rgba(204,255,0,0.1); color: #6e8f00;"
                                 onmouseover="this.style.background='#ccff00';this.style.color='#0a1221'"
                                 onmouseout="this.style.background='rgba(204,255,0,0.1)';this.style.color='#6e8f00'">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-futbol"></i>
                    </div>
                    <h3>Belum Ada Lapangan</h3>
                    <p>Belum ada lapangan yang aktif saat ini.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ============================================
     CARA KERJA
     ============================================ --}}
<section id="cara-kerja" class="section-dark py-28">
    <div class="absolute inset-0 dot-pattern"></div>
    <div class="absolute -top-40 left-1/3 w-80 h-80 rounded-full blur-[150px]" style="background: rgba(204,255,0,0.05);"></div>

    <div class="container-custom relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-16 reveal">
            <span class="text-sm font-bold uppercase tracking-widest mb-3 block" style="color: #ccff00;">Mudah & Cepat</span>
            <h2 class="text-4xl md:text-5xl font-display font-bold text-white tracking-tight mb-4">Cara Booking Mudah</h2>
            <p class="text-dark-300 text-lg leading-relaxed">Hanya butuh 4 langkah sederhana untuk mengamankan jadwal main futsalmu.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
            {{-- Connection line removed per user request --}}

            @php
            $steps = [
                ['icon' => 'fa-search', 'title' => '1. Pilih Lapangan', 'desc' => 'Cari dan pilih lapangan yang sesuai dengan kebutuhan timmu.', 'color' => '#ccff00'],
                ['icon' => 'fa-calendar-alt', 'title' => '2. Pilih Jadwal', 'desc' => 'Tentukan tanggal dan pilih slot waktu yang masih tersedia.', 'color' => '#00e5ff'],
                ['icon' => 'fa-credit-card', 'title' => '3. Bayar', 'desc' => 'Selesaikan pembayaran via transfer bank, e-wallet, atau manual.', 'color' => '#ccff00'],
                ['icon' => 'fa-qrcode', 'title' => '4. Main!', 'desc' => 'Tunjukkan QR Code booking kepada petugas di lokasi lapangan.', 'color' => '#00e5ff'],
            ];
            @endphp

            @foreach($steps as $i => $step)
            <div class="relative z-10 text-center group reveal" style="transition-delay: {{ $i * 150 }}ms;">
                <div class="w-24 h-24 mx-auto rounded-2xl flex items-center justify-center text-3xl mb-6 transition-all duration-500 group-hover:-translate-y-2"
                     style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: {{ $step['color'] }};">
                    <i class="fas {{ $step['icon'] }}"></i>
                </div>
                <h4 class="font-bold text-xl text-white mb-3">{{ $step['title'] }}</h4>
                <p class="text-dark-400 text-sm leading-relaxed max-w-[220px] mx-auto">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================
     KEUNGGULAN TEMPAT (NEW)
     ============================================ --}}
<section class="py-24 bg-white relative overflow-hidden">
    <div class="absolute -top-40 -right-40 w-80 h-80 rounded-full blur-[150px]" style="background: rgba(204,255,0,0.06);"></div>

    <div class="container-custom relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-16 reveal">
            <span class="text-sm font-bold uppercase tracking-widest mb-3 block" style="color: #8ab300;">Fasilitas Premium</span>
            <h2 class="text-4xl md:text-5xl font-display font-bold text-dark-900 tracking-tight mb-4">Kenapa Memilih Kami?</h2>
            <p class="text-dark-500 text-lg leading-relaxed">Kami menyediakan fasilitas terbaik untuk pengalaman bermain futsal yang tak terlupakan.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @php
            $keunggulan = [
                ['icon' => 'fa-futbol', 'title' => 'Lapangan Terawat', 'desc' => 'Standar internasional'],
                ['icon' => 'fa-lightbulb', 'title' => 'Pencahayaan', 'desc' => 'LED terang merata'],
                ['icon' => 'fa-car', 'title' => 'Parkir Luas', 'desc' => 'Area parkir aman'],
                ['icon' => 'fa-restroom', 'title' => 'Toilet Bersih', 'desc' => 'Selalu terjaga'],
                ['icon' => 'fa-mosque', 'title' => 'Musala', 'desc' => 'Tempat ibadah nyaman'],
                ['icon' => 'fa-shield-alt', 'title' => 'Keamanan', 'desc' => 'CCTV 24 jam'],
            ];
            @endphp

            @foreach($keunggulan as $i => $item)
            <div class="reveal text-center p-6 rounded-2xl border border-dark-100/40 hover:border-primary-300/40 transition-all duration-300 hover:-translate-y-1 group" style="transition-delay: {{ $i * 80 }}ms;">
                <div class="w-14 h-14 mx-auto rounded-2xl flex items-center justify-center mb-4 transition-all duration-300 text-xl"
                     style="background: rgba(204,255,0,0.08); color: #6e8f00;">
                    <i class="fas {{ $item['icon'] }}"></i>
                </div>
                <h4 class="font-bold text-dark-900 text-sm mb-1">{{ $item['title'] }}</h4>
                <p class="text-xs text-dark-500">{{ $item['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================
     LEADERBOARD PREVIEW
     ============================================ --}}
<section class="section-dark py-28 relative">
    <div class="absolute inset-0 grid-pattern"></div>
    <div class="absolute -top-60 -right-60 w-[500px] h-[500px] rounded-full blur-[180px]" style="background: rgba(204,255,0,0.05);"></div>
    <div class="absolute -bottom-60 -left-60 w-[500px] h-[500px] rounded-full blur-[180px]" style="background: rgba(0,229,255,0.04);"></div>

    <div class="container-custom relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-16">
            <div class="lg:w-1/2 max-w-xl reveal-left">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full font-bold text-sm mb-8"
                     style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); color: #ccff00;">
                    <i class="fas fa-fire"></i> Hall of Fame
                </div>
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-display font-bold text-white leading-[1.1] mb-6 tracking-tight">Jadilah Pencetak Gol Terbanyak!</h2>
                <p class="text-dark-300 text-lg mb-10 leading-relaxed">
                    Setiap gol yang kamu cetak dalam sesi booking akan tercatat di sistem kami. Bersainglah dengan pemain lain dan raih posisi teratas.
                </p>
                <a href="{{ route('leaderboard.index') }}" class="btn-primary text-lg px-10 py-5">
                    Lihat Leaderboard <i class="fas fa-trophy ml-2"></i>
                </a>
            </div>

            <div class="lg:w-1/2 w-full max-w-lg reveal-right">
                <div class="rounded-[2rem] p-1 shadow-2xl" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                    <div class="rounded-[calc(2rem-4px)] p-8" style="background: rgba(10,18,33,0.8);">
                        <div class="flex items-center justify-between mb-8 pb-6" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                            <h3 class="font-bold text-xl text-white">Top 5 Scorer</h3>
                            <i class="fas fa-medal text-2xl" style="color: #ccff00;"></i>
                        </div>

                        <div class="space-y-3">
                            @forelse($leaderboard as $index => $l)
                            <div class="flex items-center gap-4 p-4 rounded-2xl transition-colors"
                                 style="{{ $index === 0 ? 'background: rgba(204,255,0,0.06); border: 1px solid rgba(204,255,0,0.15);' : 'background: rgba(255,255,255,0.03);' }}">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-sm"
                                     style="{{ $index === 0 ? 'background: #ccff00; color: #0a1221;' : ($index === 1 ? 'background: #b8c9dd; color: #0a1221;' : ($index === 2 ? 'background: #8da3bd; color: #0a1221;' : 'background: rgba(255,255,255,0.06); color: #627d9e;')) }}">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold truncate" style="color: {{ $index === 0 ? '#ccff00' : '#ffffff' }};">{{ $l->nama_pemain }}</p>
                                    <p class="text-xs text-dark-400">{{ $l->total_sesi }} Sesi Bermain</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="font-display font-black text-2xl" style="color: {{ $index === 0 ? '#ccff00' : '#ffffff' }};">{{ $l->total_gol }}</p>
                                    <p class="text-[10px] uppercase tracking-widest text-dark-400 font-bold">GOL</p>
                                </div>
                            </div>
                            @empty
                            <p class="text-center text-dark-400 py-6">Belum ada data statistik.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================
     TESTIMONI
     ============================================ --}}
@if($testimoni->count() > 0)
<section class="py-24 bg-white relative overflow-hidden">
    <div class="container-custom">
        <div class="text-center max-w-3xl mx-auto mb-16 reveal">
            <span class="text-sm font-bold uppercase tracking-widest mb-3 block" style="color: #8ab300;">Testimoni</span>
            <h2 class="text-4xl md:text-5xl font-display font-bold text-dark-900 tracking-tight mb-4">Apa Kata Mereka?</h2>
            <p class="text-dark-500 text-lg leading-relaxed">Pengalaman bermain dari para member Lapsal Futsal.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($testimoni as $t)
            <div class="reveal bg-white rounded-3xl border border-dark-100/40 p-7 flex flex-col hover:-translate-y-1.5 hover:shadow-2xl transition-all duration-300" style="transition-delay: {{ $loop->index * 100 }}ms;">
                <div class="flex items-center gap-1 mb-5">
                    @for($i = 0; $i < $t->rating; $i++)
                    <i class="fas fa-star text-sm" style="color: #a3cc00;"></i>
                    @endfor
                    @for($i = 0; $i < 5 - $t->rating; $i++)
                    <i class="far fa-star text-dark-200 text-sm"></i>
                    @endfor
                </div>
                <p class="text-dark-700 italic leading-relaxed flex-1 mb-7 text-sm">"{!! nl2br(e($t->ulasan ?? 'Mantap lapangannya!')) !!}"</p>

                <div class="flex items-center gap-4 pt-5" style="border-top: 1px solid rgba(0,0,0,0.06);">
                    <img src="{{ $t->user->avatar_url }}" alt="" class="w-11 h-11 rounded-xl object-cover ring-2 ring-dark-100">
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

{{-- ============================================
     CTA FINAL (NEW)
     ============================================ --}}
<section class="relative py-28 overflow-hidden bg-mesh-dark">
    <div class="absolute inset-0 dot-pattern opacity-20"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] rounded-full blur-[180px]" style="background: rgba(204,255,0,0.08);"></div>

    <div class="container-custom relative z-10">
        <div class="text-center max-w-3xl mx-auto reveal">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl mb-8 animate-pulse-glow"
                 style="background: rgba(204,255,0,0.1); border: 1px solid rgba(204,255,0,0.2);">
                <i class="fas fa-futbol text-4xl" style="color: #ccff00;"></i>
            </div>
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-display font-black text-white tracking-tight mb-6">
                Siap Main <span class="text-gradient-premium">Futsal?</span>
            </h2>
            <p class="text-dark-300 text-xl leading-relaxed mb-10 max-w-xl mx-auto">
                Jangan tunda lagi. Booking lapangan sekarang dan ajak timmu untuk bertanding!
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('lapangan.index') }}" class="btn-primary text-lg px-12 py-5">
                    <i class="fas fa-bolt mr-2"></i> Booking Sekarang
                </a>
                @guest
                <a href="{{ route('register') }}" class="btn-outline-light text-lg px-12 py-5">
                    Daftar Gratis <i class="fas fa-arrow-right ml-2"></i>
                </a>
                @endguest
            </div>
        </div>
    </div>
</section>

@endsection

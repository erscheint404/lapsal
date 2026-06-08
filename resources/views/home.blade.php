@extends('layouts.app')

@section('content')
{{-- ============================================
     HERO — Live Dashboard Style
     ============================================ --}}
<section class="relative min-h-[100dvh] flex items-center overflow-hidden bg-gradient-dark">
    <div class="absolute inset-0 dot-pattern opacity-10"></div>
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-primary-500 via-primary-300 to-primary-500"></div>

    <div class="container-custom relative z-10 pt-32 pb-20 w-full">
        <div class="grid lg:grid-cols-12 gap-10 items-center">
            {{-- Left: Headline + CTA --}}
            <div class="lg:col-span-7 max-w-2xl" x-data="{ show: false }" x-init="setTimeout(() => show = true, 50)">
                <div class="flex items-center gap-3 px-4 py-2 rounded-full text-sm font-semibold mb-8 w-fit transition-all duration-700"
                     style="background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.2); color: #6ee7b7;"
                     :class="show ? 'translate-y-0 opacity-100' : 'translate-y-6 opacity-0'">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background: #10b981;"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2" style="background: #10b981;"></span>
                    </span>
                    <span class="font-mono font-bold">{{ $slotTersedia }}</span> slot tersedia hari ini
                </div>

                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-display font-black text-white leading-[1.05] tracking-tight transition-all duration-700 delay-100"
                    :class="show ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    Main Futsal,<br>
                    <span class="text-gradient-premium">Makin Gampang</span>
                </h1>

                <p class="text-lg text-secondary-300 mt-6 max-w-lg leading-relaxed transition-all duration-700 delay-200"
                   :class="show ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    Cek jadwal, booking lapangan, bayar online. Cuma perlu 2 menit.
                </p>

                <div class="flex flex-wrap gap-4 mt-10 transition-all duration-700 delay-300"
                     :class="show ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    <a href="{{ route('lapangan.index') }}" class="btn-primary text-lg px-10 py-5">
                        <i class="fas fa-search mr-2"></i> Cari Lapangan
                    </a>
                    <a href="#cara-kerja" class="btn-outline-light text-lg px-10 py-5">
                        Cara Kerja
                    </a>
                </div>

                <div class="flex items-center gap-8 mt-14 pt-8 transition-all duration-700 delay-400"
                     style="border-top: 1px solid rgba(255,255,255,0.06);"
                     :class="show ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    <div>
                        <p class="text-3xl font-display font-black text-white" data-count="{{ $totalMember }}">0</p>
                        <p class="text-xs font-semibold text-secondary-400 mt-1">Member Aktif</p>
                    </div>
                    <div class="w-px h-10" style="background: rgba(255,255,255,0.08);"></div>
                    <div>
                        <p class="text-3xl font-display font-black text-white" data-count="{{ $totalBooking }}">0</p>
                        <p class="text-xs font-semibold text-secondary-400 mt-1">Total Booking</p>
                    </div>
                    <div class="w-px h-10" style="background: rgba(255,255,255,0.08);"></div>
                    <div>
                        <p class="text-3xl font-display font-black text-white flex items-center gap-1">
                            {{ $rataRating }} <i class="fas fa-star text-sm" style="color: #10b981;"></i>
                        </p>
                        <p class="text-xs font-semibold text-secondary-400 mt-1">Rating</p>
                    </div>
                </div>
            </div>

            {{-- Right: Live Availability Card --}}
            <div class="lg:col-span-5 transition-all duration-700 delay-300"
                 :class="show ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0'">
                <div class="rounded-2xl border overflow-hidden" style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.08);">
                    {{-- Card header --}}
                    <div class="px-6 py-4 flex items-center justify-between" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                        <div class="flex items-center gap-2 text-sm font-semibold text-white">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Live — Slot Hari Ini
                        </div>
                        <a href="{{ route('lapangan.index') }}" class="text-xs font-semibold" style="color: #34d399;">Lihat semua →</a>
                    </div>

                    {{-- Slot list --}}
                    <div class="p-5 space-y-3 max-h-[320px] overflow-y-auto custom-scrollbar">
                        @forelse($slotHariIni->take(5) as $slot)
                        <a href="{{ route('lapangan.show', $slot->lapangan_id) }}" class="flex items-center gap-4 p-3 rounded-xl transition-all duration-200 group"
                           style="background: rgba(255,255,255,0.03);"
                           onmouseover="this.style.background='rgba(16,185,129,0.08)'"
                           onmouseout="this.style.background='rgba(255,255,255,0.03)'">
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-white text-sm truncate">{{ $slot->lapangan->nama ?? 'Lapangan' }}</p>
                                <p class="text-xs text-secondary-400 mt-0.5">
                                    {{ substr($slot->jam_mulai, 0, 5) }} - {{ substr($slot->jam_selesai, 0, 5) }} WIB
                                </p>
                            </div>
                            <span class="px-3 py-1 rounded-lg text-xs font-bold whitespace-nowrap"
                                  style="background: rgba(16,185,129,0.15); color: #34d399;">
                                Rp{{ number_format($slot->harga ?? 0, 0, ',', '.') }}
                            </span>
                        </a>
                        @empty
                        <p class="text-center text-secondary-400 text-sm py-8">Semua slot terisi hari ini.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================
     LAPANGAN — Asymmetric Gallery
     ============================================ --}}
<section class="py-24 bg-gradient-to-b from-secondary-50 to-white relative overflow-hidden">
    <div class="container-custom">
        <div class="flex items-end justify-between gap-6 mb-12 reveal">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest" style="color: #059669;">PILIHAN TERBAIK</span>
                <h2 class="text-4xl md:text-5xl font-display font-bold text-secondary-900 tracking-tight mt-2">Pilihan Lapangan</h2>
            </div>
            <a href="{{ route('lapangan.index') }}" class="btn-secondary text-sm shrink-0 hidden sm:inline-flex">
                Lihat Semua <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        @if($lapangan->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($lapangan as $l)
            <a href="{{ route('lapangan.show', $l->id) }}" class="group block reveal" style="transition-delay: {{ $loop->index * 80 }}ms;">
                <div class="bg-white rounded-2xl border border-secondary-200/40 overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1 h-full flex flex-col">
                    <div class="aspect-[4/3] overflow-hidden bg-secondary-100">
                        @if($l->foto_utama)
                        <img src="{{ Storage::url($l->foto_utama) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                        <div class="w-full h-full flex items-center justify-center"><i class="fas fa-futbol text-3xl text-secondary-300"></i></div>
                        @endif
                    </div>
                    <div class="flex-1 p-5 flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <h3 class="font-bold text-secondary-900 line-clamp-1">{{ $l->nama }}</h3>
                                <span class="flex items-center gap-1 text-xs font-bold whitespace-nowrap" style="color: #059669;">
                                    <i class="fas fa-star" style="color: #10b981;"></i> {{ $l->rata_rating }}
                                </span>
                            </div>
                            <p class="text-xs text-secondary-500">{{ ucfirst(str_replace('_', ' ', $l->tipe)) }}</p>
                        </div>
                        <div class="flex items-center justify-between mt-4 pt-4" style="border-top: 1px solid rgba(0,0,0,0.04);">
                            <p class="font-bold font-mono text-lg" style="color: #059669;">Rp{{ number_format($l->harga_per_jam, 0, ',', '.') }}<span class="text-xs font-normal text-secondary-400">/jam</span></p>
                            <span class="text-sm font-bold transition-all group-hover:translate-x-1" style="color: #10b981;">Pesan <i class="fas fa-chevron-right ml-1 text-xs"></i></span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @endif

        <div class="text-center mt-8 sm:hidden reveal">
            <a href="{{ route('lapangan.index') }}" class="btn-primary">Lihat Semua Lapangan</a>
        </div>
    </div>
</section>

{{-- ============================================
     CARA KERJA — Zig-zag Timeline
     ============================================ --}}
<section id="cara-kerja" class="section-dark py-28 relative overflow-hidden">
    <div class="absolute inset-0 dot-pattern opacity-20"></div>
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-primary-500 via-primary-300 to-primary-500"></div>

    <div class="container-custom relative z-10">
        <div class="text-center max-w-2xl mx-auto mb-20 reveal">
            <span class="text-xs font-bold uppercase tracking-widest" style="color: #34d399;">CARA KERJA</span>
            <h2 class="text-4xl md:text-5xl font-display font-bold text-white tracking-tight mt-2">Booking dalam 4 Langkah</h2>
            <p class="text-secondary-400 mt-3">Dari cari lapangan sampai main, semuanya online.</p>
        </div>

        @php
        $steps = [
            ['icon' => 'fa-search', 'title' => 'Cari Lapangan', 'desc' => 'Pilih lapangan yang cocok dari berbagai tipe dan harga. Filter berdasarkan jadwal dan fasilitas.'],
            ['icon' => 'fa-calendar-alt', 'title' => 'Pilih Jadwal', 'desc' => 'Lihat ketersediaan slot secara real-time. Pilih tanggal dan jam yang kamu mau.'],
            ['icon' => 'fa-credit-card', 'title' => 'Bayar Online', 'desc' => 'Selesaikan pembayaran via transfer bank, e-wallet, atau QRIS. Aman dan cepat.'],
            ['icon' => 'fa-qrcode', 'title' => 'Tunjuk QR, Main!', 'desc' => 'QR Code tiket dikirim otomatis. Tunjukkan ke petugas, langsung main.'],
        ];
        @endphp

        <div class="max-w-3xl mx-auto space-y-12 relative">
            {{-- Vertical line --}}
            <div class="absolute left-8 top-0 bottom-0 w-px hidden md:block" style="background: linear-gradient(180deg, rgba(16,185,129,0.4), rgba(16,185,129,0.1), transparent);"></div>

            @foreach($steps as $i => $step)
            <div class="relative flex flex-col md:flex-row items-start gap-8 md:gap-12 reveal" style="transition-delay: {{ $i * 100 }}ms;">
                {{-- Number + Icon --}}
                <div class="flex md:flex-col items-center gap-4 md:items-center shrink-0">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-2xl font-black relative z-10 shadow-lg"
                         style="background: linear-gradient(135deg, #10b981, #059669); color: white;">
                        <i class="fas {{ $step['icon'] }}"></i>
                    </div>
                    <span class="text-sm font-bold font-mono" style="color: #34d399;">0{{ $i + 1 }}</span>
                </div>

                {{-- Content --}}
                <div class="flex-1 pb-4 md:pb-8" style="{{ $i < count($steps) - 1 ? 'border-bottom: 1px solid rgba(255,255,255,0.04);' : '' }}">
                    <h3 class="text-xl font-bold text-white mb-2">{{ $step['title'] }}</h3>
                    <p class="text-secondary-400 leading-relaxed max-w-lg">{{ $step['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================
     KEUNGGULAN — Split Category Cards
     ============================================ --}}
<section class="py-24 bg-white relative overflow-hidden">
    <div class="container-custom">
        <div class="text-center max-w-2xl mx-auto mb-16 reveal">
            <span class="text-xs font-bold uppercase tracking-widest" style="color: #059669;">FASILITAS</span>
            <h2 class="text-4xl md:text-5xl font-display font-bold text-secondary-900 tracking-tight mt-2">Kenapa Pilih Kami?</h2>
        </div>

        @php
        $lapanganItems = [
            ['icon' => 'fa-futbol', 'title' => 'Lapangan Standar', 'desc' => 'Ukuran internasional, nyaman buat 5v5 atau 6v6.'],
            ['icon' => 'fa-lightbulb', 'title' => 'Pencahayaan LED', 'desc' => 'Terang merata tanpa bayangan. Cocok main malam hari.'],
            ['icon' => 'fa-wind', 'title' => 'Sirkulasi Udara', 'desc' => 'Ventilasi terbuka lebar, ngga pengap.'],
        ];
        $pendukungItems = [
            ['icon' => 'fa-car', 'title' => 'Parkir Luas & Aman', 'desc' => 'Area parkir cukup luas untuk motor dan mobil.'],
            ['icon' => 'fa-restroom', 'title' => 'Toilet & Ganti Baju', 'desc' => 'Bersih, terawat, dan ada tempat bilas.'],
            ['icon' => 'fa-mosque', 'title' => 'Musala', 'desc' => 'Tempat ibadah nyaman untuk salat.'],
            ['icon' => 'fa-shield-alt', 'title' => 'CCTV 24 Jam', 'desc' => 'Pantau terus, barang aman.'],
        ];
        @endphp

        <div class="grid md:grid-cols-2 gap-6">
            {{-- Lapangan card --}}
            <div class="rounded-2xl p-8 reveal" style="background: linear-gradient(135deg, #f0fdf4, #ffffff); border: 1px solid rgba(16,185,129,0.15);">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(16,185,129,0.1);">
                        <i class="fas fa-futbol" style="color: #10b981;"></i>
                    </div>
                    <h3 class="font-bold text-lg text-secondary-900">Fasilitas Lapangan</h3>
                </div>
                <div class="space-y-4">
                    @foreach($lapanganItems as $item)
                    <div class="flex gap-4">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 mt-0.5" style="background: rgba(16,185,129,0.06);">
                            <i class="fas {{ $item['icon'] }} text-sm" style="color: #10b981;"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-secondary-900 text-sm">{{ $item['title'] }}</p>
                            <p class="text-xs text-secondary-500 mt-0.5">{{ $item['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Pendukung card --}}
            <div class="rounded-2xl p-8 reveal" style="transition-delay: 100ms; background: linear-gradient(135deg, #f8fafc, #ffffff); border: 1px solid rgba(0,0,0,0.06);">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(16,185,129,0.1);">
                        <i class="fas fa-building" style="color: #10b981;"></i>
                    </div>
                    <h3 class="font-bold text-lg text-secondary-900">Fasilitas Pendukung</h3>
                </div>
                <div class="space-y-4">
                    @foreach($pendukungItems as $item)
                    <div class="flex gap-4">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 mt-0.5" style="background: rgba(16,185,129,0.06);">
                            <i class="fas {{ $item['icon'] }} text-sm" style="color: #10b981;"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-secondary-900 text-sm">{{ $item['title'] }}</p>
                            <p class="text-xs text-secondary-500 mt-0.5">{{ $item['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================
     LEADERBOARD — Scoreboard Style
     ============================================ --}}
<section class="section-dark py-28 relative overflow-hidden">
    <div class="absolute inset-0 grid-pattern opacity-20"></div>
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-primary-500 via-primary-300 to-primary-500"></div>

    <div class="container-custom relative z-10">
        <div class="grid lg:grid-cols-5 gap-12 items-center">
            <div class="lg:col-span-2 reveal-left">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold mb-6"
                     style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.15); color: #34d399;">
                    <i class="fas fa-trophy"></i> LEADERBOARD
                </div>
                <h2 class="text-4xl md:text-5xl font-display font-bold text-white leading-[1.1] tracking-tight">Pencetak Gol<br>Terbanyak</h2>
                <p class="text-secondary-400 mt-4 leading-relaxed">Setiap gol yang kamu cetak tercatat. Naikkan peringkatmu dan jadi yang terbaik.</p>
                <a href="{{ route('leaderboard.index') }}" class="btn-outline-light mt-8 inline-flex text-sm px-6 py-3">
                    Lihat Selengkapnya <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>

            <div class="lg:col-span-3 reveal-right">
                <div class="rounded-2xl overflow-hidden border" style="background: rgba(15,23,42,0.6); border-color: rgba(255,255,255,0.06);">
                    <div class="px-6 py-4 flex items-center justify-between" style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                        <span class="text-sm font-semibold" style="color: #94a3b8;">Top 5 Pemain</span>
                        <div class="flex items-center gap-2 text-xs">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            <span style="color: #64748b;">Live</span>
                        </div>
                    </div>
                    <div class="divide-y" style="border-color: rgba(255,255,255,0.04);">
                        @forelse($leaderboard as $index => $l)
                        <div class="flex items-center gap-4 px-6 py-4 transition-colors"
                             style="{{ $index === 0 ? 'background: rgba(16,185,129,0.04);' : '' }}">
                            <span class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm font-mono"
                                  style="{{ $index === 0 ? 'background: #10b981; color: white;' : ($index === 1 ? 'background: #475569; color: white;' : ($index === 2 ? 'background: #334155; color: white;' : 'background: rgba(255,255,255,0.04); color: #64748b;')) }}">
                                {{ $index + 1 }}
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-white text-sm truncate">{{ $l->nama_pemain }}</p>
                                <p class="text-xs" style="color: #64748b;">{{ $l->total_sesi }} sesi</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold font-mono" style="color: #34d399;">{{ $l->total_gol }}</p>
                                <p class="text-[10px] font-semibold tracking-widest" style="color: #64748b;">GOL</p>
                            </div>
                        </div>
                        @empty
                        <div class="px-6 py-10 text-center">
                            <p class="text-secondary-500">Belum ada data skor.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================
     TESTIMONI — Featured + Stacked
     ============================================ --}}
@if($testimoni->count() > 0)
<section class="py-24 bg-white relative overflow-hidden">
    <div class="container-custom">
        <div class="text-center max-w-2xl mx-auto mb-16 reveal">
            <span class="text-xs font-bold uppercase tracking-widest" style="color: #059669;">TESTIMONI</span>
            <h2 class="text-4xl md:text-5xl font-display font-bold text-secondary-900 tracking-tight mt-2">Dari Member ke Member</h2>
        </div>

        @php
        $firstTestimoni = $testimoni->shift();
        @endphp

        <div class="grid md:grid-cols-3 gap-6">
            {{-- Featured testimonial --}}
            @if($firstTestimoni)
            <div class="md:col-span-2 rounded-2xl p-8 md:p-10 reveal relative overflow-hidden"
                 style="background: linear-gradient(135deg, #f0fdf4, #ffffff); border: 1px solid rgba(16,185,129,0.15);">
                <div class="absolute top-0 right-0 w-40 h-40 rounded-full blur-[80px]" style="background: rgba(16,185,129,0.06);"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-1 mb-4">
                        @for($i = 0; $i < $firstTestimoni->rating; $i++)
                        <i class="fas fa-star text-sm" style="color: #10b981;"></i>
                        @endfor
                        @for($i = 0; $i < 5 - $firstTestimoni->rating; $i++)
                        <i class="far fa-star text-sm" style="color: #d1d5db;"></i>
                        @endfor
                    </div>
                    <p class="text-secondary-700 text-lg leading-relaxed italic">"{!! nl2br(e($firstTestimoni->ulasan ?? 'Mantap lapangannya!')) !!}"</p>
                    <div class="flex items-center gap-4 mt-6 pt-5" style="border-top: 1px solid rgba(16,185,129,0.15);">
                        <img src="{{ $firstTestimoni->user->avatar_url }}" alt="" class="w-12 h-12 rounded-xl object-cover">
                        <div>
                            <p class="font-bold text-secondary-900">{{ $firstTestimoni->user->name }}</p>
                            <p class="text-xs text-secondary-500">Main di {{ $firstTestimoni->lapangan->nama }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Remaining testimonials stacked --}}
            <div class="space-y-4">
                @forelse($testimoni as $t)
                <div class="rounded-2xl p-5 border border-secondary-200/40 bg-white reveal" style="transition-delay: {{ $loop->index * 80 }}ms;">
                    <div class="flex items-center gap-1 mb-2">
                        @for($i = 0; $i < $t->rating; $i++)
                        <i class="fas fa-star text-xs" style="color: #10b981;"></i>
                        @endfor
                    </div>
                    <p class="text-sm text-secondary-600 leading-relaxed line-clamp-2">"{!! nl2br(e($t->ulasan ?? 'Mantap!')) !!}"</p>
                    <div class="flex items-center gap-2 mt-3">
                        <img src="{{ $t->user->avatar_url }}" alt="" class="w-7 h-7 rounded-lg object-cover">
                        <p class="text-xs font-semibold text-secondary-800">{{ $t->user->name }}</p>
                    </div>
                </div>
                @empty
                <p class="text-center text-secondary-400 text-sm py-4">Testimoni lainnya tidak tersedia.</p>
                @endforelse
            </div>
        </div>
    </div>
</section>
@endif

{{-- ============================================
     CTA — Event Poster Style
     ============================================ --}}
<section class="relative py-28 overflow-hidden bg-gradient-dark">
    <div class="absolute inset-0 dot-pattern opacity-10"></div>
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-primary-500 via-primary-300 to-primary-500"></div>

    <div class="container-custom relative z-10 text-center reveal">
        <div class="max-w-2xl mx-auto">
            <div class="inline-flex items-center gap-3 px-5 py-2 rounded-full text-sm font-semibold mb-8"
                 style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.15); color: #34d399;">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                {{ $slotTersedia }} slot tersedia
            </div>
            <h2 class="text-5xl md:text-6xl font-display font-black text-white leading-[1.05] tracking-tight">
                Siap Main<br><span class="text-gradient-premium">Futsal?</span>
            </h2>
            <p class="text-secondary-400 text-lg mt-4 max-w-md mx-auto">
                Booking sekarang. Ajak timmu. Langsung main.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-10">
                <a href="{{ route('lapangan.index') }}" class="btn-primary text-lg px-12 py-5 shadow-2xl">
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

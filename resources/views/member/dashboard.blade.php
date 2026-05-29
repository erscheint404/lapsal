@extends('layouts.app')
@section('title', 'Dashboard Member')
@section('content')
<div class="section-gradient min-h-screen pt-28 pb-16">
    <div class="container-custom max-w-6xl">
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6 reveal">
            <div>
                <span class="text-sm font-bold uppercase tracking-widest mb-2 block" style="color: #6e8f00;">Member Area</span>
                <h1 class="text-3xl lg:text-4xl font-display font-black text-dark-900 tracking-tight">Halo, {{ auth()->user()->name }}!</h1>
                <p class="text-dark-500 mt-2 leading-relaxed text-lg">Siap untuk pertandingan selanjutnya?</p>
            </div>
            <a href="{{ route('lapangan.index') }}" class="btn-primary shadow-xl shrink-0">
                <i class="fas fa-bolt mr-2"></i>Booking Lapangan
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                {{-- Stats --}}
                <div class="grid grid-cols-2 gap-5 reveal-left">
                    <div class="stat-card border-0 relative overflow-hidden" style="background: linear-gradient(135deg, #1a2740, #0a1221); color: white;">
                        <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full blur-[40px]" style="background: rgba(204,255,0,0.15);"></div>
                        <div class="stat-icon relative z-10" style="background: rgba(204,255,0,0.1); border: 1px solid rgba(204,255,0,0.2);">
                            <i class="fas fa-check-circle" style="color: #ccff00;"></i>
                        </div>
                        <div class="relative z-10">
                            <p class="text-sm font-bold uppercase tracking-widest mb-1 text-dark-300">Booking Selesai</p>
                            <p class="text-4xl font-display font-black">{{ $bookingSelesai }} <span class="text-lg font-normal text-dark-400">Sesi</span></p>
                        </div>
                    </div>
                    <div class="card-premium p-7 flex items-center gap-5">
                        <div class="stat-icon" style="background: rgba(0,229,255,0.1); border: 1px solid rgba(0,229,255,0.2);">
                            <i class="fas fa-wallet" style="color: #00b3cc;"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-dark-500 uppercase tracking-widest mb-1">Total Pengeluaran</p>
                            <p class="text-2xl font-display font-black text-dark-900">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Active Bookings --}}
                <div class="card-premium p-8 reveal" style="transition-delay: 100ms;">
                    <div class="flex items-center justify-between mb-8 pb-4" style="border-bottom: 1px solid rgba(0,0,0,0.06);">
                        <h3 class="text-xl font-bold text-dark-900 flex items-center gap-2">
                            <i class="fas fa-calendar-alt" style="color: #6e8f00;"></i> Booking Aktif
                        </h3>
                        <a href="{{ route('member.booking.index') }}" class="text-sm font-bold hover:underline transition-colors" style="color: #6e8f00;">Lihat Semua <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                    <div class="space-y-4">
                        @forelse($bookingAktif as $booking)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-5 rounded-2xl border transition-all duration-300 group"
                             style="background: white; border-color: rgba(0,0,0,0.06);"
                             onmouseover="this.style.borderColor='rgba(204,255,0,0.4)';this.style.boxShadow='0 10px 30px rgba(0,0,0,0.05)'"
                             onmouseout="this.style.borderColor='rgba(0,0,0,0.06)';this.style.boxShadow='none'">
                            <div class="flex items-center gap-5 mb-4 sm:mb-0">
                                <div class="w-16 h-16 rounded-xl overflow-hidden bg-dark-100 hidden sm:block shadow-sm shrink-0 border border-dark-100">
                                    @if($booking->lapangan->foto_utama)
                                    <img src="{{ Storage::url($booking->lapangan->foto_utama) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                    <div class="w-full h-full flex items-center justify-center text-dark-300"><i class="fas fa-image"></i></div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-dark-900 text-lg mb-1">{{ $booking->lapangan->nama }}</p>
                                    <div class="flex items-center gap-4 text-sm font-medium text-dark-500">
                                        <span class="flex items-center gap-1.5"><i class="far fa-calendar-alt" style="color: #6e8f00;"></i> {{ $booking->tanggal->format('d M Y') }}</span>
                                        <span class="flex items-center gap-1.5"><i class="far fa-clock" style="color: #6e8f00;"></i> {{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-center gap-3 border-t sm:border-t-0 pt-3 sm:pt-0" style="border-color: rgba(0,0,0,0.06);">
                                <span class="badge" style="background: {{ in_array($booking->status, ['confirmed']) ? 'rgba(204,255,0,0.15)' : (in_array($booking->status, ['pending_payment', 'under_review']) ? 'rgba(0,229,255,0.1)' : 'rgba(0,0,0,0.05)') }}; color: {{ in_array($booking->status, ['confirmed']) ? '#526b00' : (in_array($booking->status, ['pending_payment', 'under_review']) ? '#007a8f' : '#46607f') }}; border: 1px solid {{ in_array($booking->status, ['confirmed']) ? 'rgba(204,255,0,0.3)' : (in_array($booking->status, ['pending_payment', 'under_review']) ? 'rgba(0,229,255,0.2)' : 'rgba(0,0,0,0.1)') }};">
                                    {{ $booking->status_label }}
                                </span>
                                <a href="{{ in_array($booking->status, ['pending_payment', 'draft']) ? route('member.booking.checkout', $booking->id) : route('member.booking.show', $booking->id) }}"
                                   class="btn-secondary btn-sm">
                                    Detail <i class="fas fa-chevron-right ml-1 text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="empty-state">
                            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background: rgba(204,255,0,0.1); color: #6e8f00;">
                                <i class="fas fa-calendar-times text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-dark-900 mb-1">Tidak Ada Booking Aktif</h3>
                            <p class="text-sm text-dark-500 mb-6">Anda belum memiliki jadwal bermain saat ini.</p>
                            <a href="{{ route('lapangan.index') }}" class="btn-primary">Mulai Booking</a>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-8 reveal-right">
                {{-- Profile Card --}}
                <div class="card-premium p-8 text-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-24" style="background: linear-gradient(135deg, #1a2740, #0a1221);"></div>
                    <div class="relative w-28 h-28 mx-auto mb-5 group mt-4">
                        <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-full h-full rounded-2xl object-cover border-4 border-white shadow-xl relative z-10 bg-white">
                        <div class="absolute inset-0 rounded-2xl z-20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300" style="background: rgba(10,18,33,0.6); backdrop-filter: blur(4px);">
                            <a href="{{ route('member.profil.index') }}" class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-dark-900 hover:scale-110 transition-transform">
                                <i class="fas fa-pen"></i>
                            </a>
                        </div>
                    </div>
                    <h3 class="font-display font-black text-2xl text-dark-900 mb-1">{{ auth()->user()->name }}</h3>
                    <p class="text-sm font-medium text-dark-500 mb-6">{{ auth()->user()->email }}</p>
                    <a href="{{ route('member.profil.index') }}" class="btn-secondary w-full">Edit Profil</a>
                </div>

                {{-- Rating Prompt --}}
                @if($bisaDirating->count() > 0)
                <div class="card-premium p-7 relative overflow-hidden" style="border: 1px solid rgba(204,255,0,0.3);">
                    <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full blur-[40px]" style="background: rgba(204,255,0,0.15);"></div>
                    <div class="flex items-center gap-3 mb-5 relative z-10">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(204,255,0,0.15); color: #6e8f00;">
                            <i class="fas fa-star text-lg"></i>
                        </div>
                        <h3 class="font-bold text-dark-900 text-lg">Beri Ulasan</h3>
                    </div>
                    <p class="text-sm font-medium text-dark-600 leading-relaxed mb-5 relative z-10">Bagaimana pengalaman bermain Anda? Beri rating lapangan yang baru Anda gunakan.</p>
                    <div class="space-y-3 relative z-10">
                        @foreach($bisaDirating as $booking)
                        <div class="bg-white p-4 rounded-2xl border border-dark-100/60 shadow-sm hover:shadow-md transition-all group"
                             onmouseover="this.style.borderColor='rgba(204,255,0,0.4)'"
                             onmouseout="this.style.borderColor='rgba(0,0,0,0.06)'">
                            <p class="font-bold text-sm text-dark-900 line-clamp-1 mb-1">{{ $booking->lapangan->nama }}</p>
                            <p class="text-xs font-medium text-dark-400 mb-3"><i class="far fa-calendar-alt mr-1"></i> {{ $booking->tanggal->format('d M Y') }}</p>
                            <a href="{{ route('member.booking.show', $booking->id) }}#rating" class="text-xs font-bold flex items-center" style="color: #6e8f00;">
                                Tulis Ulasan <i class="fas fa-arrow-right ml-1 transition-transform group-hover:translate-x-1"></i>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')
@section('title', 'Dashboard Member')
@section('content')
<div class="bg-dark-50 py-12 min-h-screen">
    <div class="container-custom max-w-6xl">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark-900">Halo, {{ auth()->user()->name }}! ⚽</h1>
                <p class="text-dark-500 mt-1">Selamat datang kembali di Lapsal Futsal.</p>
            </div>
            <a href="{{ route('lapangan.index') }}" class="btn-primary hidden md:inline-flex">
                <i class="fas fa-plus mr-2"></i>Booking Baru
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- Stats Grid --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="card p-6 bg-gradient-to-br from-emerald-500 to-teal-600 text-white border-0">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-2xl mb-4 backdrop-blur-sm">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                        <p class="text-sm font-medium text-emerald-100 mb-1">Booking Selesai</p>
                        <p class="text-3xl font-display font-black">{{ $bookingSelesai }} <span class="text-lg font-normal">Sesi</span></p>
                    </div>
                    
                    <div class="card p-6">
                        <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center text-2xl mb-4">
                            <i class="fas fa-wallet text-primary-500"></i>
                        </div>
                        <p class="text-sm font-medium text-dark-500 mb-1">Total Pengeluaran</p>
                        <p class="text-2xl font-display font-black text-dark-900">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                    </div>
                </div>

                {{-- Active Bookings --}}
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-dark-900">Booking Aktif</h3>
                        <a href="{{ route('member.booking.index') }}" class="text-sm text-primary-600 font-medium hover:text-primary-700">Lihat Semua</a>
                    </div>

                    <div class="space-y-4">
                        @forelse($bookingAktif as $booking)
                        <div class="flex items-center justify-between p-4 rounded-xl border border-dark-100 hover:border-primary-200 hover:bg-primary-50/30 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-xl overflow-hidden bg-dark-100 hidden sm:block">
                                    @if($booking->lapangan->foto_utama)
                                    <img src="{{ Storage::url($booking->lapangan->foto_utama) }}" class="w-full h-full object-cover">
                                    @else
                                    <div class="w-full h-full flex items-center justify-center text-dark-300"><i class="fas fa-image"></i></div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-dark-900 mb-1">{{ $booking->lapangan->nama }}</p>
                                    <div class="flex items-center gap-3 text-sm text-dark-500">
                                        <span><i class="far fa-calendar-alt mr-1"></i> {{ $booking->tanggal->format('d M Y') }}</span>
                                        <span><i class="far fa-clock mr-1"></i> {{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="mb-2">
                                    <span class="badge {{ $booking->status_color }}">{{ $booking->status_label }}</span>
                                </div>
                                <a href="{{ in_array($booking->status, ['pending_payment', 'draft']) ? route('member.booking.checkout', $booking->id) : route('member.booking.show', $booking->id) }}" class="btn-secondary btn-sm">
                                    Detail <i class="fas fa-chevron-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 bg-dark-50 rounded-xl border border-dashed border-dark-200 text-dark-500">
                            Tidak ada booking aktif saat ini.<br>
                            <a href="{{ route('lapangan.index') }}" class="text-primary-600 font-medium mt-2 inline-block">Mulai booking sekarang</a>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-8">
                
                {{-- User Profile Card --}}
                <div class="card p-6 text-center">
                    <div class="relative w-24 h-24 mx-auto mb-4 group">
                        <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-full h-full rounded-full object-cover border-4 border-white shadow-lg">
                        <a href="{{ route('member.profil.index') }}" class="absolute inset-0 bg-dark-900/40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity backdrop-blur-sm">
                            <i class="fas fa-pen text-white"></i>
                        </a>
                    </div>
                    <h3 class="font-bold text-lg text-dark-900">{{ auth()->user()->name }}</h3>
                    <p class="text-sm text-dark-500 mb-4">{{ auth()->user()->email }}</p>
                    <a href="{{ route('member.profil.index') }}" class="btn-secondary w-full">Edit Profil</a>
                </div>

                {{-- Need Rating --}}
                @if($bisaDirating->count() > 0)
                <div class="card p-6 border-amber-200 bg-gradient-to-b from-amber-50 to-white">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-500 flex items-center justify-center">
                            <i class="fas fa-star"></i>
                        </div>
                        <h3 class="font-bold text-dark-900">Beri Ulasan</h3>
                    </div>
                    <p class="text-sm text-dark-600 mb-4">Bagaimana pengalaman bermain Anda? Beri rating lapangan yang baru Anda gunakan.</p>
                    
                    <div class="space-y-3">
                        @foreach($bisaDirating as $booking)
                        <div class="bg-white p-3 rounded-xl border border-amber-100 shadow-sm">
                            <p class="font-bold text-sm text-dark-900 line-clamp-1">{{ $booking->lapangan->nama }}</p>
                            <p class="text-xs text-dark-400 mb-2">{{ $booking->tanggal->format('d M Y') }}</p>
                            <a href="{{ route('member.booking.show', $booking->id) }}#rating" class="text-xs font-bold text-amber-600 hover:text-amber-700 flex items-center">
                                Tulis Ulasan <i class="fas fa-arrow-right ml-1"></i>
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

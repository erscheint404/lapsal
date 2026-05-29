@extends('layouts.app')
@section('title', 'Notifikasi')
@section('content')
<div class="section-gradient min-h-screen pt-28 pb-16">
    <div class="container-custom max-w-4xl">

        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4 reveal">
            <div>
                <span class="text-sm font-bold uppercase tracking-widest mb-2 block" style="color: #6e8f00;">Aktivitas Member</span>
                <h1 class="text-3xl lg:text-4xl font-display font-black text-dark-900 tracking-tight">Notifikasi</h1>
                <p class="text-dark-500 mt-2 leading-relaxed">Pemberitahuan terkait booking dan akun Anda.</p>
            </div>
            <button class="btn-secondary text-sm shadow-sm hover:shadow-md">
                <i class="fas fa-check-double mr-2"></i>Tandai Semua Dibaca
            </button>
        </div>

        <div class="card-premium overflow-hidden reveal-scale" style="transition-delay: 100ms;">
            <div class="divide-y divide-dark-100/60">

                {{-- Demo Notification Items (Since DB is not fully implemented yet) --}}
                <div class="p-6 flex gap-5 hover:bg-dark-50/50 transition-colors group relative" style="background: rgba(204,255,0,0.03);">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary-500"></div>
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 shadow-sm" style="background: white; border: 1px solid rgba(204,255,0,0.3); color: #6e8f00;">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-1 gap-2">
                            <h4 class="font-bold text-dark-900 text-lg">Booking Dikonfirmasi!</h4>
                            <span class="text-xs font-bold text-dark-400 flex items-center gap-1.5"><i class="far fa-clock"></i> 5 menit yang lalu</span>
                        </div>
                        <p class="text-dark-600 text-sm leading-relaxed mb-3">Hore! Pembayaran untuk booking Lapangan Vinyl A pada 25 Mei 2024 telah kami terima. Siapkan tim Anda!</p>
                        <a href="#" class="inline-flex items-center text-sm font-bold transition-colors" style="color: #6e8f00;">Lihat E-Ticket <i class="fas fa-arrow-right ml-1.5 text-xs"></i></a>
                    </div>
                </div>

                <div class="p-6 flex gap-5 hover:bg-dark-50/50 transition-colors group">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 border shadow-sm" style="background: white; border-color: rgba(0,229,255,0.3); color: #007a8f;">
                        <i class="fas fa-wallet text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-1 gap-2">
                            <h4 class="font-bold text-dark-900 text-lg">Menunggu Pembayaran</h4>
                            <span class="text-xs font-bold text-dark-400 flex items-center gap-1.5"><i class="far fa-clock"></i> 1 jam yang lalu</span>
                        </div>
                        <p class="text-dark-600 text-sm leading-relaxed mb-3">Silakan selesaikan pembayaran sebesar Rp 150.000 untuk booking Lapangan Sintetis sebelum waktu habis.</p>
                        <a href="#" class="inline-flex items-center text-sm font-bold transition-colors" style="color: #00b3cc;">Bayar Sekarang <i class="fas fa-arrow-right ml-1.5 text-xs"></i></a>
                    </div>
                </div>

                <div class="p-6 flex gap-5 hover:bg-dark-50/50 transition-colors group">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 border border-dark-200/60 shadow-sm bg-white text-dark-400">
                        <i class="fas fa-user-check text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-1 gap-2">
                            <h4 class="font-bold text-dark-900 text-lg text-dark-600">Selamat Datang di Lapsal!</h4>
                            <span class="text-xs font-bold text-dark-400 flex items-center gap-1.5"><i class="far fa-clock"></i> 2 hari yang lalu</span>
                        </div>
                        <p class="text-dark-500 text-sm leading-relaxed">Terima kasih telah mendaftar. Mulai booking lapangan pertama Anda sekarang dan raih posisi top scorer!</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
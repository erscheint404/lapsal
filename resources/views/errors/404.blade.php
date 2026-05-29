@extends('layouts.app')
@section('title', 'Halaman Tidak Ditemukan')
@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-16 px-4 bg-mesh-dark relative overflow-hidden">
    <div class="absolute inset-0 dot-pattern opacity-15"></div>
    <div class="absolute top-20 left-[20%] w-2 h-2 rounded-full animate-float" style="background: #ccff00; box-shadow: 0 0 15px rgba(204,255,0,0.4);"></div>
    <div class="absolute bottom-32 right-[15%] w-1.5 h-1.5 rounded-full animate-float" style="background: #00e5ff; box-shadow: 0 0 12px rgba(0,229,255,0.4); animation-delay: 1s;"></div>

    <div class="text-center max-w-lg relative z-10" x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">
        <div class="mb-8 transition-all duration-700" :class="show ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
            <div class="relative inline-block">
                <span class="text-[10rem] md:text-[14rem] font-display font-black leading-none tracking-tighter"
                      style="color: transparent; -webkit-text-stroke: 2px rgba(204,255,0,0.2);">404</span>
                <span class="absolute inset-0 flex items-center justify-center text-[10rem] md:text-[14rem] font-display font-black leading-none tracking-tighter text-gradient-premium animate-pulse-glow" style="box-shadow: none;">404</span>
            </div>
        </div>
        <p class="text-2xl font-bold text-white mb-2 transition-all duration-700 delay-100" :class="show ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">Halaman Tidak Ditemukan</p>
        <p class="text-dark-400 mb-10 leading-relaxed transition-all duration-700 delay-200" :class="show ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
            Halaman yang Anda cari mungkin telah dipindahkan, dihapus, atau tidak pernah ada. Yuk balik ke beranda!
        </p>
        <div class="flex items-center justify-center gap-4 transition-all duration-700 delay-300" :class="show ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
            <a href="{{ route('home') }}" class="btn-primary">
                <i class="fas fa-home mr-2"></i> Kembali ke Beranda
            </a>
            <a href="{{ route('lapangan.index') }}" class="btn-outline-light">
                Cari Lapangan
            </a>
        </div>
    </div>
</div>
@endsection

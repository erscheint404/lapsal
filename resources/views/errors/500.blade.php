@extends('layouts.app')
@section('title', 'Terjadi Kesalahan')
@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-16 px-4 bg-mesh-dark relative overflow-hidden">
    <div class="absolute inset-0 dot-pattern opacity-15"></div>

    <div class="text-center max-w-lg relative z-10" x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">
        <div class="mb-8 transition-all duration-700" :class="show ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
            <div class="w-28 h-28 rounded-[2rem] flex items-center justify-center mx-auto animate-pulse-glow"
                 style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2);">
                <i class="fas fa-exclamation-triangle text-red-400 text-5xl"></i>
            </div>
        </div>
        <h1 class="text-7xl md:text-8xl font-display font-black tracking-tight mb-4 transition-all duration-700 delay-100"
            style="color: transparent; background: linear-gradient(135deg, #ef4444, #f97316); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"
            :class="show ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">500</h1>
        <p class="text-2xl font-bold text-white mb-2 transition-all duration-700 delay-200" :class="show ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">Terjadi Kesalahan</p>
        <p class="text-dark-400 mb-10 leading-relaxed transition-all duration-700 delay-300" :class="show ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
            Maaf, terjadi kesalahan pada server. Tim kami sudah mendapat notifikasi dan akan segera memperbaikinya.
        </p>
        <div class="flex items-center justify-center gap-4 transition-all duration-700 delay-400" :class="show ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
            <a href="{{ route('home') }}" class="btn-primary">
                <i class="fas fa-home mr-2"></i> Kembali ke Beranda
            </a>
            <a href="javascript:location.reload()" class="btn-outline-light">
                <i class="fas fa-redo mr-2"></i> Coba Lagi
            </a>
        </div>
    </div>
</div>
@endsection

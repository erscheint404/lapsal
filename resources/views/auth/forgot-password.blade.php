@extends('layouts.app')
@section('title', 'Lupa Password')
@section('content')
<div class="min-h-[85vh] flex items-center justify-center py-16 px-4 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-primary-50 via-white to-emerald-50 -z-10"></div>
    <div class="absolute -top-40 -right-40 w-80 h-80 bg-primary-200/20 rounded-full blur-[128px] -z-10"></div>
    <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-emerald-200/20 rounded-full blur-[128px] -z-10"></div>

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-2xl shadow-amber-500/30">
                <i class="fas fa-key text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-display font-bold text-dark-900 tracking-tight">Lupa Password?</h1>
            <p class="text-dark-500 mt-2">Masukkan email Anda untuk reset password</p>
        </div>

        <div class="bg-white rounded-3xl border border-dark-100/80 shadow-xl shadow-dark-200/10 p-8">
            @if (session('status'))
            <div class="bg-emerald-50 border border-emerald-200/60 text-emerald-700 px-5 py-3.5 rounded-2xl mb-6 text-sm font-semibold">
                {{ session('status') }}
            </div>
            @endif
            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="form-label">Email</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-400"><i class="fas fa-envelope"></i></span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-input pl-11" placeholder="nama@email.com" required autofocus>
                    </div>
                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="btn-primary w-full">
                    <i class="fas fa-paper-plane mr-2"></i> Kirim Link Reset
                </button>
            </form>
        </div>

        <p class="text-center mt-6 text-sm text-dark-500">
            Ingat password? <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-bold">Masuk</a>
        </p>
    </div>
</div>
@endsection

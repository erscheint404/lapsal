@extends('layouts.app')
@section('title', 'Masuk')
@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-primary rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-primary-500/30">
                <i class="fas fa-futbol text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-display font-bold text-dark-900">Selamat Datang</h1>
            <p class="text-dark-500 mt-2">Masuk ke akun Lapsal Futsal Anda</p>
        </div>

        {{-- Login Form --}}
        <div class="card p-8">
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="form-label">Email</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-400">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               class="form-input pl-11" placeholder="nama@email.com" required autofocus>
                    </div>
                    @error('email')
                    <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="form-label">Password</label>
                    <div class="relative" x-data="{ show: false }">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-400">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input :type="show ? 'text' : 'password'" id="password" name="password"
                               class="form-input pl-11 pr-11" placeholder="••••••••" required>
                        <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-dark-400 hover:text-dark-600">
                            <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </button>
                    </div>
                    @error('password')
                    <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember & Forgot --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-dark-300 text-primary-600 focus:ring-primary-500">
                        <span class="text-sm text-dark-600">Ingat saya</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">Lupa password?</a>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-primary w-full">
                    <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                </button>
            </form>

            {{-- Divider --}}
            <div class="flex items-center gap-4 my-6">
                <div class="flex-1 h-px bg-dark-200"></div>
                <span class="text-sm text-dark-400 font-medium">atau</span>
                <div class="flex-1 h-px bg-dark-200"></div>
            </div>

            {{-- Google Login --}}
            <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-3 px-6 py-3 bg-white border-2 border-dark-200 rounded-xl font-semibold text-dark-700 hover:border-dark-300 hover:bg-dark-50 transition-all duration-200">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Masuk dengan Google
            </a>
        </div>

        {{-- Register Link --}}
        <p class="text-center mt-6 text-sm text-dark-500">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-700 font-semibold">Daftar sekarang</a>
        </p>
    </div>
</div>
@endsection

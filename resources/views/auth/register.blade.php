@extends('layouts.app')
@section('title', 'Daftar')
@section('content')
<div class="min-h-screen flex relative overflow-hidden">
    {{-- Left side — Brand visual --}}
    <div class="hidden lg:flex lg:w-1/2 relative items-center justify-center bg-mesh-dark">
        <div class="absolute inset-0 dot-pattern opacity-20"></div>
        <div class="absolute top-32 right-20 w-2 h-2 rounded-full animate-float" style="background: #00e5ff; box-shadow: 0 0 15px rgba(0,229,255,0.4);"></div>
        <div class="absolute bottom-28 left-20 w-1.5 h-1.5 rounded-full animate-float" style="background: #ccff00; box-shadow: 0 0 12px rgba(204,255,0,0.4); animation-delay: 1.5s;"></div>

        <div class="relative z-10 text-center px-16 max-w-lg">
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-8"
                 style="background: rgba(0,229,255,0.1); border: 1px solid rgba(0,229,255,0.2);">
                <i class="fas fa-users text-4xl" style="color: #00e5ff;"></i>
            </div>
            <h2 class="text-4xl font-display font-black text-white tracking-tight mb-4">Gabung <span class="text-gradient-premium">Sekarang</span></h2>
            <p class="text-dark-300 text-lg leading-relaxed">Daftar gratis dan nikmati kemudahan booking lapangan futsal kapan saja, di mana saja.</p>

            <div class="mt-12 space-y-4 text-left max-w-sm mx-auto pt-8" style="border-top: 1px solid rgba(255,255,255,0.06);">
                @php
                $benefits = [
                    ['icon' => 'fa-bolt', 'text' => 'Booking cepat dalam hitungan menit'],
                    ['icon' => 'fa-qrcode', 'text' => 'QR Code tiket digital otomatis'],
                    ['icon' => 'fa-star', 'text' => 'Beri rating & lihat leaderboard'],
                    ['icon' => 'fa-bell', 'text' => 'Notifikasi real-time booking'],
                ];
                @endphp
                @foreach($benefits as $b)
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background: rgba(204,255,0,0.1);">
                        <i class="fas {{ $b['icon'] }} text-sm" style="color: #ccff00;"></i>
                    </div>
                    <p class="text-sm text-dark-300">{{ $b['text'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Right side — Register form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center py-16 px-6 bg-dark-50">
        <div class="w-full max-w-md">
            <div class="text-center mb-8 lg:text-left">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-5 lg:mx-0 mx-auto"
                     style="background: linear-gradient(135deg, #00e5ff, #00b3cc); box-shadow: 0 4px 20px rgba(0,229,255,0.25);">
                    <i class="fas fa-user-plus text-dark-950 text-xl"></i>
                </div>
                <h1 class="text-3xl font-display font-bold text-dark-900 tracking-tight">Buat Akun</h1>
                <p class="text-dark-500 mt-2">Bergabung dan mulai booking lapangan futsal</p>
            </div>

            <div class="card-premium p-8">
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-400"><i class="fas fa-user"></i></span>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-input pl-11" placeholder="Nama lengkap" required autofocus>
                        </div>
                        @error('name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="form-label">Email</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-400"><i class="fas fa-envelope"></i></span>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-input pl-11" placeholder="nama@email.com" required>
                        </div>
                        @error('email') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="phone" class="form-label">Nomor HP</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-400"><i class="fas fa-phone"></i></span>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="form-input pl-11" placeholder="08xxxxxxxxxx" required>
                        </div>
                        @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="form-label">Password</label>
                        <div class="relative" x-data="{ show: false }">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-400"><i class="fas fa-lock"></i></span>
                            <input :type="show ? 'text' : 'password'" id="password" name="password" class="form-input pl-11 pr-11" placeholder="Minimal 8 karakter" required>
                            <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-dark-400 hover:text-dark-600 transition-colors">
                                <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                        @error('password') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-400"><i class="fas fa-lock"></i></span>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-input pl-11" placeholder="Ulangi password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-glow w-full">
                        <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
                    </button>
                </form>

                <div class="flex items-center gap-4 my-7">
                    <div class="flex-1 h-px bg-dark-200/60"></div>
                    <span class="text-sm font-semibold text-dark-400">atau</span>
                    <div class="flex-1 h-px bg-dark-200/60"></div>
                </div>

                <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-3 px-6 py-3.5 bg-white border-2 border-dark-200/60 rounded-2xl font-bold text-dark-700 hover:border-dark-300 hover:bg-dark-50 transition-all duration-200">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Daftar dengan Google
                </a>
            </div>

            <p class="text-center mt-6 text-sm text-dark-500">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-bold" style="color: #6e8f00;">Masuk di sini</a>
            </p>
        </div>
    </div>
</div>
@endsection

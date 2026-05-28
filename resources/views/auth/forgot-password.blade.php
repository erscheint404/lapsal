@extends('layouts.app')
@section('title', 'Lupa Password')
@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-amber-500/30">
                <i class="fas fa-key text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-display font-bold text-dark-900">Lupa Password?</h1>
            <p class="text-dark-500 mt-2">Masukkan email Anda untuk reset password</p>
        </div>
        <div class="card p-8">
            @if (session('status'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 text-sm">
                {{ session('status') }}
            </div>
            @endif
            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="nama@email.com" required autofocus>
                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="btn-primary w-full">
                    <i class="fas fa-paper-plane mr-2"></i>Kirim Link Reset
                </button>
            </form>
        </div>
        <p class="text-center mt-6 text-sm text-dark-500">
            Ingat password? <a href="{{ route('login') }}" class="text-primary-600 font-semibold">Masuk</a>
        </p>
    </div>
</div>
@endsection

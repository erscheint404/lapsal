@extends('layouts.app')
@section('title', 'Reset Password')
@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-display font-bold text-dark-900">Reset Password</h1>
            <p class="text-dark-500 mt-2">Buat password baru untuk akun Anda</p>
        </div>
        <div class="card p-8">
            <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div>
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-input" required>
                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="password" class="form-label">Password Baru</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Minimal 8 karakter" required>
                    @error('password') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required>
                </div>
                <button type="submit" class="btn-primary w-full">
                    <i class="fas fa-save mr-2"></i>Reset Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

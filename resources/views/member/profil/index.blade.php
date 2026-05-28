@extends('layouts.app')
@section('title', 'Profil Saya')
@section('content')
<div class="bg-dark-50 py-12 min-h-screen">
    <div class="container-custom max-w-4xl">
        <div class="mb-8">
            <h1 class="text-3xl font-display font-bold text-dark-900">Pengaturan Profil</h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-1 space-y-4" x-data="{ tab: 'profil' }">
                <button @click="tab = 'profil'" class="w-full text-left px-4 py-3 rounded-xl font-medium transition-colors" :class="tab === 'profil' ? 'bg-primary-500 text-white shadow-md' : 'bg-white text-dark-600 hover:bg-dark-100'">
                    <i class="fas fa-user mr-2"></i> Data Diri
                </button>
                <button @click="tab = 'password'" class="w-full text-left px-4 py-3 rounded-xl font-medium transition-colors" :class="tab === 'password' ? 'bg-primary-500 text-white shadow-md' : 'bg-white text-dark-600 hover:bg-dark-100'">
                    <i class="fas fa-lock mr-2"></i> Keamanan
                </button>
            </div>

            <div class="md:col-span-2">
                {{-- Form Profil --}}
                <div class="card p-6 md:p-8" x-show="tab === 'profil'">
                    <h3 class="font-bold text-xl text-dark-900 mb-6 border-b border-dark-100 pb-4">Informasi Pribadi</h3>
                    
                    <form action="{{ route('member.profil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        @method('PUT')
                        
                        <div class="flex items-center gap-6 mb-6">
                            <div class="w-20 h-20 rounded-full overflow-hidden bg-dark-100 border-4 border-white shadow-lg">
                                <img src="{{ $user->avatar_url }}" alt="Avatar" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <label class="btn-secondary btn-sm cursor-pointer inline-flex">
                                    <i class="fas fa-camera mr-2"></i> Ganti Foto
                                    <input type="file" name="avatar" class="hidden" accept="image/*">
                                </label>
                                <p class="text-xs text-dark-400 mt-2">Format: JPG, PNG. Max: 2MB.</p>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
                            </div>
                            
                            <div>
                                <label class="form-label">Email <span class="text-xs font-normal text-red-500 ml-1">(Tidak dapat diubah)</span></label>
                                <input type="email" value="{{ $user->email }}" class="form-input bg-dark-50 text-dark-500 cursor-not-allowed" readonly disabled>
                            </div>
                            
                            <div>
                                <label class="form-label">No. HP / WhatsApp</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input" required>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->detailMember->tanggal_lahir?->format('Y-m-d')) }}" class="form-input">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" rows="3" class="form-input">{{ old('alamat', $user->detailMember->alamat) }}</textarea>
                            </div>
                        </div>
                        
                        <div class="pt-4 text-right">
                            <button type="submit" class="btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>

                {{-- Form Password --}}
                <div class="card p-6 md:p-8" x-show="tab === 'password'" style="display: none;">
                    <h3 class="font-bold text-xl text-dark-900 mb-6 border-b border-dark-100 pb-4">Ubah Password</h3>
                    
                    @if($user->google_id && !$user->password)
                    <div class="bg-blue-50 text-blue-700 p-4 rounded-xl text-sm mb-6">
                        Anda mendaftar menggunakan Google. Jika ingin membuat password untuk login manual, gunakan fitur Lupa Password di halaman login.
                    </div>
                    @else
                    <form action="{{ route('member.profil.password') }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label class="form-label">Password Saat Ini</label>
                            <input type="password" name="current_password" class="form-input" required>
                            @error('current_password') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-input" required>
                            <p class="form-help">Minimal 8 karakter.</p>
                            @error('password') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-input" required>
                        </div>
                        
                        <div class="pt-4 text-right">
                            <button type="submit" class="btn-primary">Update Password</button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

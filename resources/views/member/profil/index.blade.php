@extends('layouts.app')
@section('title', 'Profil Saya')
@section('content')
<div class="section-gradient min-h-screen pt-28 pb-16">
    <div class="container-custom max-w-4xl">
        <div class="mb-10 text-center reveal">
            <span class="text-sm font-bold uppercase tracking-widest mb-2 block" style="color: #6e8f00;">Pengaturan Akun</span>
            <h1 class="text-3xl lg:text-4xl font-display font-black text-dark-900 tracking-tight">Profil Saya</h1>
            <p class="text-dark-500 mt-2 leading-relaxed">Kelola informasi data diri dan keamanan akun Anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Navigation Sidebar --}}
            <div class="md:col-span-1 reveal-left">
                <div class="card-premium p-4 sticky top-28">
                    <nav class="space-y-2 flex flex-row md:flex-col overflow-x-auto md:overflow-visible hide-scrollbar" id="profile-tabs">
                        <button onclick="switchTab('profile')" id="tab-profile" class="tab-btn w-full flex items-center gap-3 px-4 py-3 text-left font-bold rounded-xl transition-all active-tab shrink-0 md:shrink"
                                style="background: rgba(204,255,0,0.1); color: #526b00; border: 1px solid rgba(204,255,0,0.3);">
                            <i class="fas fa-user w-5 text-center"></i> Data Diri
                        </button>
                        <button onclick="switchTab('security')" id="tab-security" class="tab-btn w-full flex items-center gap-3 px-4 py-3 text-left font-medium text-dark-500 rounded-xl transition-all hover:bg-dark-50 shrink-0 md:shrink"
                                style="border: 1px solid transparent;">
                            <i class="fas fa-lock w-5 text-center"></i> Keamanan
                        </button>
                    </nav>
                </div>
            </div>

            {{-- Content Area --}}
            <div class="md:col-span-2 reveal-right">
                {{-- Profile Content --}}
                <div id="content-profile" class="card-premium p-8 transition-opacity duration-300">
                    <form action="{{ route('member.profil.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Avatar Section --}}
                        <div class="flex flex-col sm:flex-row items-center gap-6 mb-8 pb-8 border-b border-dark-100/60">
                            <div class="relative group">
                                <div class="w-24 h-24 rounded-2xl overflow-hidden border-2 border-dark-100 bg-white p-1">
                                    <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" id="avatar-preview" class="w-full h-full object-cover rounded-xl">
                                </div>
                                <label for="avatar" class="absolute inset-0 rounded-2xl bg-dark-900/50 flex flex-col items-center justify-center text-white opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity backdrop-blur-sm">
                                    <i class="fas fa-camera text-xl mb-1"></i>
                                    <span class="text-[10px] font-bold">Ubah Foto</span>
                                </label>
                                <input type="file" id="avatar" name="avatar" class="hidden" accept="image/*" onchange="previewAvatar(event)">
                            </div>
                            <div class="text-center sm:text-left">
                                <h3 class="font-bold text-dark-900 text-lg mb-1">Foto Profil</h3>
                                <p class="text-sm text-dark-500 mb-3">Format JPG, JPEG, PNG. Maksimal 2MB.</p>
                                @error('avatar')
                                    <span class="text-xs text-red-500 font-medium">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Form Fields --}}
                        <div class="space-y-5">
                            <div>
                                <label class="form-label">Nama Lengkap</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-400"><i class="fas fa-user"></i></span>
                                    <input type="text" name="name" class="form-input pl-11" value="{{ old('name', auth()->user()->name) }}" required>
                                </div>
                                @error('name')<p class="form-error">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="form-label">Email</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-400"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-input pl-11 bg-dark-50 text-dark-500 cursor-not-allowed" value="{{ auth()->user()->email }}" disabled>
                                </div>
                                <p class="text-[10px] font-bold text-dark-400 uppercase mt-1.5"><i class="fas fa-info-circle mr-1"></i>Email tidak dapat diubah.</p>
                            </div>

                            <div>
                                <label class="form-label">Nomor Telepon / WhatsApp</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-400"><i class="fas fa-phone"></i></span>
                                    <input type="text" name="phone" class="form-input pl-11" value="{{ old('phone', auth()->user()->phone) }}" placeholder="Contoh: 081234567890">
                                </div>
                                @error('phone')<p class="form-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="pt-4 mt-6" style="border-top: 1px solid rgba(0,0,0,0.06);">
                                <button type="submit" class="btn-primary w-full sm:w-auto px-8">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Security Content --}}
                <div id="content-security" class="card-premium p-8 hidden opacity-0 transition-opacity duration-300">
                    <div class="flex items-center gap-4 mb-6 pb-6 border-b border-dark-100/60">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background: rgba(0,229,255,0.1); color: #007a8f;">
                            <i class="fas fa-shield-alt text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-dark-900 text-lg mb-1">Ubah Password</h3>
                            <p class="text-sm text-dark-500">Pastikan akun Anda menggunakan password yang kuat.</p>
                        </div>
                    </div>

                    <form action="{{ route('member.profil.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-5">
                            @if(auth()->user()->password)
                            <div>
                                <label class="form-label">Password Saat Ini</label>
                                <div class="relative" x-data="{ show: false }">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-400"><i class="fas fa-lock"></i></span>
                                    <input :type="show ? 'text' : 'password'" name="current_password" class="form-input pl-11 pr-11" required>
                                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-dark-400 hover:text-dark-600">
                                        <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                    </button>
                                </div>
                                @error('current_password')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            @else
                            <div class="p-4 rounded-xl text-sm font-medium mb-4 flex items-start gap-3" style="background: rgba(0,229,255,0.05); color: #007a8f; border: 1px solid rgba(0,229,255,0.2);">
                                <i class="fas fa-info-circle mt-0.5"></i>
                                Anda login menggunakan Google, silakan buat password baru jika ingin login menggunakan email dan password.
                            </div>
                            @endif

                            <div>
                                <label class="form-label">Password Baru</label>
                                <div class="relative" x-data="{ show: false }">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-400"><i class="fas fa-key"></i></span>
                                    <input :type="show ? 'text' : 'password'" name="password" class="form-input pl-11 pr-11" required>
                                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-dark-400 hover:text-dark-600">
                                        <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                    </button>
                                </div>
                                @error('password')<p class="form-error">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-400"><i class="fas fa-check-double"></i></span>
                                    <input type="password" name="password_confirmation" class="form-input pl-11" required>
                                </div>
                            </div>

                            <div class="pt-4 mt-6" style="border-top: 1px solid rgba(0,0,0,0.06);">
                                <button type="submit" class="btn-primary w-full sm:w-auto px-8" style="background: linear-gradient(135deg, #00e5ff, #00b3cc); color: #0a1221; box-shadow: 0 4px 15px rgba(0,229,255,0.25);">Update Password</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewAvatar(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('avatar-preview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function switchTab(tab) {
        // Reset all tabs
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('font-bold', 'active-tab');
            btn.classList.add('font-medium', 'text-dark-500', 'hover:bg-dark-50');
            btn.style.background = 'transparent';
            btn.style.color = '';
            btn.style.border = '1px solid transparent';
        });

        // Set active tab styling based on type
        const activeBtn = document.getElementById(`tab-${tab}`);
        activeBtn.classList.add('font-bold', 'active-tab');
        activeBtn.classList.remove('font-medium', 'text-dark-500', 'hover:bg-dark-50');

        if(tab === 'profile') {
            activeBtn.style.background = 'rgba(204,255,0,0.1)';
            activeBtn.style.color = '#526b00';
            activeBtn.style.border = '1px solid rgba(204,255,0,0.3)';
        } else {
            activeBtn.style.background = 'rgba(0,229,255,0.1)';
            activeBtn.style.color = '#007a8f';
            activeBtn.style.border = '1px solid rgba(0,229,255,0.3)';
        }

        // Hide all content with fade
        document.querySelectorAll('[id^="content-"]').forEach(content => {
            content.classList.add('opacity-0');
            setTimeout(() => {
                content.classList.add('hidden');
            }, 300); // match transition duration
        });

        // Show target content
        setTimeout(() => {
            const targetContent = document.getElementById(`content-${tab}`);
            targetContent.classList.remove('hidden');
            // trigger reflow
            void targetContent.offsetWidth;
            targetContent.classList.remove('opacity-0');
        }, 300);
    }

    // Check URL hash for tab selection on load
    document.addEventListener('DOMContentLoaded', () => {
        if(window.location.hash === '#security') {
            switchTab('security');
        }
    });
</script>
@endpush
@endsection
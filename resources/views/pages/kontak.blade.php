@extends('layouts.app')
@section('title', 'Kontak')
@section('content')
<section class="section-dark pt-32 pb-16 relative">
    <div class="absolute inset-0 dot-pattern opacity-20"></div>
    <div class="container-custom relative z-10">
        <div class="text-center max-w-3xl mx-auto">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-6" style="background: rgba(204,255,0,0.1); border: 1px solid rgba(204,255,0,0.2);">
                <i class="fas fa-paper-plane text-3xl" style="color: #ccff00;"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-display font-bold text-white tracking-tight mb-4">Ada yang bisa kami bantu?</h1>
            <p class="text-dark-300 text-lg leading-relaxed">Jangan ragu untuk menghubungi tim Lapsal Futsal.</p>
        </div>
    </div>
</section>

<section class="py-16 bg-dark-50 -mt-1">
    <div class="container-custom">
        <div class="grid lg:grid-cols-2 gap-12 max-w-5xl mx-auto">
            {{-- Contact Form --}}
            <div class="reveal">
                <div class="card-premium p-8 shadow-xl">
                    <h3 class="text-xl font-bold text-dark-900 mb-6">Kirim Pesan</h3>
                    <form class="space-y-5">
                        @csrf
                        <div>
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-input" placeholder="Nama Anda">
                        </div>
                        <div>
                            <label class="form-label">Email</label>
                            <input type="email" class="form-input" placeholder="nama@email.com">
                        </div>
                        <div>
                            <label class="form-label">Pesan</label>
                            <textarea class="form-textarea h-32" placeholder="Tulis pesan Anda..."></textarea>
                        </div>
                        <button type="submit" class="btn-primary w-full">
                            <i class="fas fa-paper-plane mr-2"></i> Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>

            {{-- Contact Info --}}
            <div class="space-y-5 reveal-right">
                <div class="card-premium p-6 hover:shadow-lg transition-all duration-300 group">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 transition-all duration-300" style="background: rgba(204,255,0,0.08);">
                            <i class="fas fa-map-marker-alt text-xl" style="color: #6e8f00;"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-dark-900 mb-1">Alamat</h4>
                            <p class="text-dark-500 text-sm leading-relaxed">{{ \App\Models\Pengaturan::getValue('alamat', 'Jl. Contoh No. 123, Jakarta') }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-premium p-6 hover:shadow-lg transition-all duration-300 group">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0" style="background: rgba(204,255,0,0.08);">
                            <i class="fas fa-phone-alt text-xl" style="color: #6e8f00;"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-dark-900 mb-1">Telepon / WhatsApp</h4>
                            <p class="text-dark-500 text-sm">{{ \App\Models\Pengaturan::getValue('telepon', '0822-1504-2019') }}</p>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Pengaturan::getValue('telepon', '6282215042019')) }}" target="_blank" class="font-semibold text-sm inline-flex items-center gap-1 mt-1.5 transition-colors" style="color: #6e8f00;">
                                <i class="fab fa-whatsapp"></i> Chat via WhatsApp
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-premium p-6 hover:shadow-lg transition-all duration-300 group">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0" style="background: rgba(204,255,0,0.08);">
                            <i class="fas fa-envelope text-xl" style="color: #6e8f00;"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-dark-900 mb-1">Email</h4>
                            <p class="text-dark-500 text-sm">{{ \App\Models\Pengaturan::getValue('email', 'info@lapsal.com') }}</p>
                            <a href="mailto:{{ \App\Models\Pengaturan::getValue('email', 'info@lapsal.com') }}" class="font-semibold text-sm inline-flex items-center gap-1 mt-1.5 transition-colors" style="color: #6e8f00;">
                                <i class="fas fa-envelope"></i> Kirim Email
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Operating Hours --}}
                <div class="rounded-3xl p-7 text-white relative overflow-hidden" style="background: linear-gradient(135deg, #0f1b2e, #1a2740); border: 1px solid rgba(204,255,0,0.1);">
                    <div class="absolute top-0 right-0 w-32 h-32 rounded-full blur-[60px]" style="background: rgba(204,255,0,0.06);"></div>
                    <h4 class="font-bold text-lg mb-4 flex items-center gap-2">
                        <i class="fas fa-clock" style="color: #ccff00;"></i> Jam Operasional
                    </h4>
                    <div class="space-y-3 text-sm relative z-10">
                        <div class="flex justify-between items-center">
                            <span class="text-dark-300">Senin - Jumat</span>
                            <span class="font-bold text-white px-3 py-1 rounded-lg" style="background: rgba(204,255,0,0.1);">08:00 - 22:00</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-dark-300">Sabtu</span>
                            <span class="font-bold text-white px-3 py-1 rounded-lg" style="background: rgba(204,255,0,0.1);">08:00 - 23:00</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-dark-300">Minggu</span>
                            <span class="font-bold text-white px-3 py-1 rounded-lg" style="background: rgba(204,255,0,0.1);">09:00 - 21:00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

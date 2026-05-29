<footer class="relative overflow-hidden" style="background: linear-gradient(180deg, #0a1221 0%, #060d18 100%);">
    {{-- Decorative elements --}}
    <div class="absolute top-0 left-0 right-0 h-px" style="background: linear-gradient(90deg, transparent, rgba(204,255,0,0.2), rgba(0,229,255,0.2), transparent);"></div>
    <div class="absolute -top-40 left-1/4 w-80 h-80 rounded-full blur-[150px]" style="background: rgba(204,255,0,0.04);"></div>
    <div class="absolute -bottom-40 right-1/4 w-80 h-80 rounded-full blur-[150px]" style="background: rgba(0,229,255,0.04);"></div>

    <div class="container-custom relative z-10 pt-20 pb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
            {{-- Brand Column --}}
            <div class="lg:col-span-1">
                <a href="/" class="flex items-center gap-3 mb-6 group">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-dark-950 transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300"
                         style="background: linear-gradient(135deg, #ccff00 0%, #a3cc00 100%); box-shadow: 0 4px 20px rgba(204,255,0,0.25);">
                        <i class="fas fa-futbol text-xl"></i>
                    </div>
                    <span class="font-display font-black text-2xl tracking-tight text-white">
                        {{ \App\Models\Pengaturan::getValue('nama_web', 'Lapsal') }}
                    </span>
                </a>
                <p class="text-sm text-dark-400 leading-relaxed mb-6 max-w-xs">
                    Platform penyewaan lapangan futsal modern, cepat, dan terpercaya. Temukan lapangan terbaik untuk timmu hanya dalam beberapa klik.
                </p>
                <div class="flex gap-3">
                    <a href="#" class="w-10 h-10 rounded-xl flex items-center justify-center text-dark-400 transition-all duration-300 hover:scale-110" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.06);" onmouseover="this.style.background='rgba(204,255,0,0.15)';this.style.color='#ccff00';this.style.borderColor='rgba(204,255,0,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.06)';this.style.color='';this.style.borderColor='rgba(255,255,255,0.06)'"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="w-10 h-10 rounded-xl flex items-center justify-center text-dark-400 transition-all duration-300 hover:scale-110" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.06);" onmouseover="this.style.background='rgba(204,255,0,0.15)';this.style.color='#ccff00';this.style.borderColor='rgba(204,255,0,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.06)';this.style.color='';this.style.borderColor='rgba(255,255,255,0.06)'"><i class="fab fa-tiktok"></i></a>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Pengaturan::getValue('telepon', '6282215042019')) }}" target="_blank" class="w-10 h-10 rounded-xl flex items-center justify-center text-dark-400 transition-all duration-300 hover:scale-110" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.06);" onmouseover="this.style.background='rgba(0,229,255,0.15)';this.style.color='#00e5ff';this.style.borderColor='rgba(0,229,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.06)';this.style.color='';this.style.borderColor='rgba(255,255,255,0.06)'"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <h4 class="text-white font-bold mb-6 tracking-wide uppercase text-sm">Tautan Cepat</h4>
                <ul class="space-y-3.5">
                    <li><a href="{{ route('home') }}" class="text-sm text-dark-400 hover:text-primary-300 transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-[8px] text-dark-600"></i>Beranda</a></li>
                    <li><a href="{{ route('lapangan.index') }}" class="text-sm text-dark-400 hover:text-primary-300 transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-[8px] text-dark-600"></i>Cari Lapangan</a></li>
                    <li><a href="{{ route('leaderboard.index') }}" class="text-sm text-dark-400 hover:text-primary-300 transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-[8px] text-dark-600"></i>Top Scorer</a></li>
                    <li><a href="{{ route('faq') }}" class="text-sm text-dark-400 hover:text-primary-300 transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-[8px] text-dark-600"></i>FAQ</a></li>
                </ul>
            </div>

            {{-- Services --}}
            <div>
                <h4 class="text-white font-bold mb-6 tracking-wide uppercase text-sm">Layanan</h4>
                <ul class="space-y-3.5">
                    <li><a href="{{ route('register') }}" class="text-sm text-dark-400 hover:text-primary-300 transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-[8px] text-dark-600"></i>Daftar Member</a></li>
                    <li><a href="{{ route('login') }}" class="text-sm text-dark-400 hover:text-primary-300 transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-[8px] text-dark-600"></i>Login Akun</a></li>
                    <li><a href="#" class="text-sm text-dark-400 hover:text-primary-300 transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-[8px] text-dark-600"></i>Syarat & Ketentuan</a></li>
                    <li><a href="#" class="text-sm text-dark-400 hover:text-primary-300 transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-[8px] text-dark-600"></i>Kebijakan Privasi</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h4 class="text-white font-bold mb-6 tracking-wide uppercase text-sm">Hubungi Kami</h4>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 mt-0.5" style="background: rgba(204,255,0,0.08); border: 1px solid rgba(204,255,0,0.12);">
                            <i class="fas fa-map-marker-alt text-sm" style="color: #ccff00;"></i>
                        </div>
                        <span class="text-sm text-dark-400">{{ \App\Models\Pengaturan::getValue('alamat', 'Jl. Contoh No. 123, Jakarta') }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background: rgba(204,255,0,0.08); border: 1px solid rgba(204,255,0,0.12);">
                            <i class="fas fa-phone-alt text-sm" style="color: #ccff00;"></i>
                        </div>
                        <span class="text-sm text-dark-400">{{ \App\Models\Pengaturan::getValue('telepon', '0822-1504-2019') }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background: rgba(204,255,0,0.08); border: 1px solid rgba(204,255,0,0.12);">
                            <i class="fas fa-envelope text-sm" style="color: #ccff00;"></i>
                        </div>
                        <span class="text-sm text-dark-400">{{ \App\Models\Pengaturan::getValue('email', 'info@lapsal.com') }}</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="pt-8 flex flex-col md:flex-row items-center justify-between gap-4" style="border-top: 1px solid rgba(255,255,255,0.06);">
            <p class="text-sm text-dark-500">
                &copy; {{ date('Y') }} {{ \App\Models\Pengaturan::getValue('nama_web', 'Lapsal Futsal') }}. All rights reserved.
            </p>
            <div class="flex items-center gap-2 text-sm text-dark-500">
                Dibuat dengan <i class="fas fa-heart mx-1" style="color: #ccff00;"></i> untuk pecinta futsal
            </div>
        </div>
    </div>
</footer>

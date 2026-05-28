<footer class="bg-dark-900 text-dark-300 pt-16 pb-8 border-t border-dark-800">
    <div class="container-custom">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            {{-- Brand --}}
            <div class="lg:col-span-1">
                <a href="/" class="flex items-center gap-2 mb-6 inline-block group">
                    <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center text-white transform group-hover:rotate-12 transition-transform shadow-lg shadow-emerald-500/20 inline-flex">
                        <i class="fas fa-futbol text-xl"></i>
                    </div>
                    <span class="font-display font-black text-2xl tracking-tight text-white ml-2 align-middle">
                        {{ \App\Models\Pengaturan::getValue('nama_web', 'Lapsal') }}
                    </span>
                </a>
                <p class="text-sm text-dark-400 leading-relaxed mb-6">
                    Platform penyewaan lapangan futsal modern, cepat, dan terpercaya. Temukan lapangan terbaik untuk timmu hanya dalam beberapa klik.
                </p>
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-dark-800 flex items-center justify-center text-dark-400 hover:bg-emerald-500 hover:text-white transition-colors"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-dark-800 flex items-center justify-center text-dark-400 hover:bg-emerald-500 hover:text-white transition-colors"><i class="fab fa-tiktok"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-dark-800 flex items-center justify-center text-dark-400 hover:bg-emerald-500 hover:text-white transition-colors"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>

            {{-- Tautan Cepat --}}
            <div>
                <h4 class="text-white font-bold mb-6 tracking-wide">Tautan Cepat</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('home') }}" class="text-sm hover:text-emerald-400 transition-colors">Beranda</a></li>
                    <li><a href="{{ route('lapangan.index') }}" class="text-sm hover:text-emerald-400 transition-colors">Cari Lapangan</a></li>
                    <li><a href="{{ route('leaderboard.index') }}" class="text-sm hover:text-emerald-400 transition-colors">Top Scorer</a></li>
                    <li><a href="#" class="text-sm hover:text-emerald-400 transition-colors">Cara Booking</a></li>
                </ul>
            </div>

            {{-- Layanan --}}
            <div>
                <h4 class="text-white font-bold mb-6 tracking-wide">Layanan</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('register') }}" class="text-sm hover:text-emerald-400 transition-colors">Daftar Member</a></li>
                    <li><a href="{{ route('login') }}" class="text-sm hover:text-emerald-400 transition-colors">Login Akun</a></li>
                    <li><a href="#" class="text-sm hover:text-emerald-400 transition-colors">Syarat & Ketentuan</a></li>
                    <li><a href="#" class="text-sm hover:text-emerald-400 transition-colors">Kebijakan Privasi</a></li>
                </ul>
            </div>

            {{-- Kontak --}}
            <div>
                <h4 class="text-white font-bold mb-6 tracking-wide">Hubungi Kami</h4>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt text-emerald-500 mt-1"></i>
                        <span class="text-sm">{{ \App\Models\Pengaturan::getValue('alamat', 'Jl. Contoh No. 123, Jakarta') }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-phone-alt text-emerald-500"></i>
                        <span class="text-sm">{{ \App\Models\Pengaturan::getValue('telepon', '0812-3456-7890') }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-envelope text-emerald-500"></i>
                        <span class="text-sm">{{ \App\Models\Pengaturan::getValue('email', 'info@lapsal.com') }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="pt-8 border-t border-dark-800 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-sm text-dark-500">
                &copy; {{ date('Y') }} {{ \App\Models\Pengaturan::getValue('nama_web', 'Lapsal Futsal') }}. All rights reserved.
            </p>
            <div class="flex items-center gap-2 text-sm text-dark-500">
                Dibuat dengan <i class="fas fa-heart text-red-500"></i> untuk pecinta futsal.
            </div>
        </div>
    </div>
</footer>

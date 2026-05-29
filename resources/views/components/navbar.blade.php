<nav class="fixed top-0 inset-x-0 z-50 transition-all duration-500"
     x-data="{ scrolled: false, mobileMenuOpen: false }"
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     :class="scrolled ? 'py-2' : 'py-4'">
    {{-- Glassmorphism background --}}
    <div class="absolute inset-0 transition-all duration-500"
         :class="scrolled ? 'opacity-100' : 'opacity-0'"
         style="background: rgba(10, 18, 33, 0.85); backdrop-filter: blur(20px) saturate(180%); border-bottom: 1px solid rgba(255,255,255,0.06);"></div>
    <div class="absolute inset-0 transition-all duration-500"
         :class="scrolled ? 'opacity-0' : 'opacity-100'"
         style="background: transparent;"></div>

    <div class="container-custom relative z-10">
        <div class="flex items-center justify-between">
            {{-- Logo --}}
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-dark-950 transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300"
                     style="background: linear-gradient(135deg, #ccff00 0%, #a3cc00 100%); box-shadow: 0 4px 20px rgba(204,255,0,0.3);">
                    <i class="fas fa-futbol text-xl"></i>
                </div>
                <span class="font-display font-black text-2xl tracking-tight text-white">
                    {{ \App\Models\Pengaturan::getValue('nama_web', 'Lapsal') }}
                </span>
            </a>

            {{-- Desktop Navigation --}}
            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route('home') }}" class="px-4 py-2.5 font-semibold rounded-xl transition-all duration-200 text-sm {{ request()->routeIs('home') ? 'text-primary-300 bg-white/10' : 'text-dark-300 hover:text-white hover:bg-white/5' }}">Beranda</a>
                <a href="{{ route('lapangan.index') }}" class="px-4 py-2.5 font-semibold rounded-xl transition-all duration-200 text-sm {{ request()->routeIs('lapangan.*') ? 'text-primary-300 bg-white/10' : 'text-dark-300 hover:text-white hover:bg-white/5' }}">Lapangan</a>
                <a href="{{ route('leaderboard.index') }}" class="px-4 py-2.5 font-semibold rounded-xl transition-all duration-200 text-sm {{ request()->routeIs('leaderboard.*') ? 'text-primary-300 bg-white/10' : 'text-dark-300 hover:text-white hover:bg-white/5' }}">
                    <i class="fas fa-trophy text-amber-400 mr-1.5 text-xs"></i>Leaderboard
                </a>
                <a href="{{ route('faq') }}" class="px-4 py-2.5 font-semibold rounded-xl transition-all duration-200 text-sm {{ request()->routeIs('faq') ? 'text-primary-300 bg-white/10' : 'text-dark-300 hover:text-white hover:bg-white/5' }}">FAQ</a>
                <a href="{{ route('kontak') }}" class="px-4 py-2.5 font-semibold rounded-xl transition-all duration-200 text-sm {{ request()->routeIs('kontak') ? 'text-primary-300 bg-white/10' : 'text-dark-300 hover:text-white hover:bg-white/5' }}">Kontak</a>
            </div>

            {{-- Right side actions --}}
            <div class="hidden md:flex items-center gap-3">
                @auth
                    @if(auth()->user()->role->slug === 'member')
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 hover:bg-white/5 p-1.5 pr-3 rounded-full transition-colors focus:outline-none border border-transparent hover:border-white/10">
                                <div class="relative">
                                    <img src="{{ auth()->user()->avatar_url }}" class="w-9 h-9 rounded-full object-cover border-2 border-white/20 shadow-sm">
                                    @php $unreadCount = auth()->user()->unreadNotifikasi()->count(); @endphp
                                    @if($unreadCount > 0)
                                    <span class="absolute -top-1 -right-1 w-5 h-5 text-dark-950 text-[10px] font-bold flex items-center justify-center rounded-full border-2 border-dark-900" style="background: #ccff00;">{{ $unreadCount }}</span>
                                    @endif
                                </div>
                                <div class="text-left hidden lg:block">
                                    <p class="text-sm font-bold text-white leading-none">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-dark-400">Member</p>
                                </div>
                                <i class="fas fa-chevron-down text-xs text-dark-400 ml-1 hidden lg:block transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="open" x-transition.opacity.duration.200ms
                                 class="absolute right-0 mt-3 w-60 rounded-2xl shadow-2xl overflow-hidden z-50 py-2"
                                 style="display: none; background: rgba(15,27,46,0.98); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1);">
                                <div class="px-4 py-3 border-b" style="border-color: rgba(255,255,255,0.06);">
                                    <p class="font-bold text-white text-sm">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-dark-400">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('member.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-dark-300 hover:bg-white/5 hover:text-primary-300 transition-colors">
                                    <i class="fas fa-home w-5 text-center"></i> Dashboard
                                </a>
                                <a href="{{ route('member.booking.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-dark-300 hover:bg-white/5 hover:text-primary-300 transition-colors">
                                    <i class="fas fa-calendar-check w-5 text-center"></i> Riwayat Booking
                                </a>
                                <a href="{{ route('member.notifikasi.index') }}" class="flex items-center justify-between px-4 py-2.5 text-sm text-dark-300 hover:bg-white/5 hover:text-primary-300 transition-colors">
                                    <div class="flex items-center gap-3"><i class="fas fa-bell w-5 text-center"></i> Notifikasi</div>
                                    @if($unreadCount > 0)<span class="text-dark-950 text-xs px-1.5 py-0.5 rounded-md font-bold" style="background: #ccff00;">{{ $unreadCount }}</span>@endif
                                </a>
                                <a href="{{ route('member.profil.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-dark-300 hover:bg-white/5 hover:text-primary-300 transition-colors" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                                    <i class="fas fa-user-cog w-5 text-center"></i> Pengaturan Profil
                                </a>
                                <form action="{{ route('logout') }}" method="POST" class="px-2 mt-2">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-3 w-full px-2 py-2 text-sm text-red-400 hover:bg-red-500/10 rounded-xl transition-colors text-left font-semibold">
                                        <i class="fas fa-sign-out-alt w-5 text-center"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('admin.dashboard') }}" class="btn-primary btn-sm">
                            <i class="fas fa-shield-alt mr-2"></i> Panel {{ ucfirst(auth()->user()->role->nama) }}
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2.5 font-bold text-sm text-dark-300 hover:text-white transition-colors rounded-xl hover:bg-white/5">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-primary btn-sm">Daftar</a>
                @endauth
            </div>

            {{-- Mobile menu toggle --}}
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden w-10 h-10 flex items-center justify-center rounded-xl text-white hover:bg-white/10 transition-colors focus:outline-none">
                <i class="fas text-xl" :class="mobileMenuOpen ? 'fa-times' : 'fa-bars'"></i>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="mobileMenuOpen" x-transition style="display: none;"
         class="md:hidden absolute top-full left-0 w-full shadow-2xl py-6 px-6 flex flex-col gap-3"
         style="background: rgba(10,18,33,0.97); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(255,255,255,0.06);">
        <a href="{{ route('home') }}" class="font-bold text-white text-lg py-3 px-3 rounded-xl hover:bg-white/5 transition-colors">Beranda</a>
        <a href="{{ route('lapangan.index') }}" class="font-bold text-white text-lg py-3 px-3 rounded-xl hover:bg-white/5 transition-colors">Cari Lapangan</a>
        <a href="{{ route('leaderboard.index') }}" class="font-bold text-lg py-3 px-3 rounded-xl hover:bg-white/5 transition-colors" style="color: #ccff00;"><i class="fas fa-trophy mr-2"></i>Leaderboard</a>
        <a href="{{ route('faq') }}" class="font-bold text-white text-lg py-3 px-3 rounded-xl hover:bg-white/5 transition-colors">FAQ</a>
        <a href="{{ route('kontak') }}" class="font-bold text-white text-lg py-3 px-3 rounded-xl hover:bg-white/5 transition-colors">Kontak</a>

        @auth
            @if(auth()->user()->role->slug === 'member')
                <div class="pt-4 mt-2" style="border-top: 1px solid rgba(255,255,255,0.06);">
                    <p class="text-[10px] font-bold text-dark-500 uppercase tracking-widest mb-3 px-3">Akun Saya</p>
                    <a href="{{ route('member.dashboard') }}" class="flex items-center gap-3 font-medium text-dark-300 py-2 px-3 rounded-xl hover:bg-white/5 transition-colors"><i class="fas fa-home w-6"></i> Dashboard</a>
                    <a href="{{ route('member.booking.index') }}" class="flex items-center gap-3 font-medium text-dark-300 py-2 px-3 rounded-xl hover:bg-white/5 transition-colors"><i class="fas fa-calendar-check w-6"></i> Riwayat Booking</a>
                    <a href="{{ route('member.notifikasi.index') }}" class="flex items-center gap-3 font-medium text-dark-300 py-2 px-3 rounded-xl hover:bg-white/5 transition-colors"><i class="fas fa-bell w-6"></i> Notifikasi</a>
                    <form action="{{ route('logout') }}" method="POST" class="mt-4 px-3">
                        @csrf
                        <button type="submit" class="btn-danger w-full btn-sm"><i class="fas fa-sign-out-alt mr-2"></i> Keluar</button>
                    </form>
                </div>
            @else
                <a href="{{ route('admin.dashboard') }}" class="btn-primary w-full text-center mt-2">Panel {{ ucfirst(auth()->user()->role->nama) }}</a>
            @endif
        @else
            <div class="flex gap-3 mt-4">
                <a href="{{ route('login') }}" class="flex-1 text-center py-3 rounded-xl font-bold text-white border border-white/20 hover:bg-white/5 transition-colors">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary flex-1 text-center">Daftar</a>
            </div>
        @endauth
    </div>
</nav>

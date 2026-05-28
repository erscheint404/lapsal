<nav class="fixed top-0 inset-x-0 bg-white/80 backdrop-blur-lg border-b border-dark-100 z-50 transition-all duration-300" 
     x-data="{ scrolled: false, mobileMenuOpen: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     :class="scrolled ? 'shadow-md py-3' : 'py-4'">
    <div class="container-custom">
        <div class="flex items-center justify-between">
            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2 group">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center text-white transform group-hover:rotate-12 transition-transform shadow-lg shadow-emerald-500/30">
                    <i class="fas fa-futbol text-xl"></i>
                </div>
                <span class="font-display font-black text-2xl tracking-tight text-dark-900 group-hover:text-emerald-600 transition-colors">
                    {{ \App\Models\Pengaturan::getValue('nama_web', 'Lapsal') }}
                </span>
            </a>

            {{-- Desktop Menu --}}
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('home') }}" class="font-medium text-dark-600 hover:text-primary-600 transition-colors {{ request()->routeIs('home') ? 'text-primary-600 font-bold' : '' }}">Beranda</a>
                <a href="{{ route('lapangan.index') }}" class="font-medium text-dark-600 hover:text-primary-600 transition-colors {{ request()->routeIs('lapangan.*') ? 'text-primary-600 font-bold' : '' }}">Lapangan</a>
                <a href="{{ route('leaderboard.index') }}" class="font-medium text-dark-600 hover:text-primary-600 transition-colors flex items-center gap-1 {{ request()->routeIs('leaderboard.*') ? 'text-primary-600 font-bold' : '' }}"><i class="fas fa-fire text-amber-500"></i> Leaderboard</a>
                <a href="#cara-kerja" class="font-medium text-dark-600 hover:text-primary-600 transition-colors">Cara Kerja</a>
            </div>

            {{-- Desktop Actions --}}
            <div class="hidden md:flex items-center gap-4">
                @auth
                    @if(auth()->user()->role->slug === 'member')
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 hover:bg-dark-50 p-1.5 rounded-full transition-colors focus:outline-none">
                                <div class="relative">
                                    <img src="{{ auth()->user()->avatar_url }}" class="w-9 h-9 rounded-full object-cover border-2 border-primary-100">
                                    @php $unreadCount = auth()->user()->unreadNotifikasi()->count(); @endphp
                                    @if($unreadCount > 0)
                                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-[9px] font-bold flex items-center justify-center rounded-full border border-white">{{ $unreadCount }}</span>
                                    @endif
                                </div>
                                <div class="text-left hidden lg:block">
                                    <p class="text-sm font-bold text-dark-900 leading-none">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-dark-500">Member</p>
                                </div>
                                <i class="fas fa-chevron-down text-xs text-dark-400 ml-1 hidden lg:block" :class="open ? 'transform rotate-180' : ''"></i>
                            </button>
                            
                            {{-- Dropdown --}}
                            <div x-show="open" x-transition.opacity.duration.200ms style="display: none;" 
                                 class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-dark-100 overflow-hidden z-50 py-2">
                                <a href="{{ route('member.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-dark-700 hover:bg-primary-50 hover:text-primary-700 transition-colors">
                                    <i class="fas fa-home w-5 text-center"></i> Dashboard
                                </a>
                                <a href="{{ route('member.booking.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-dark-700 hover:bg-primary-50 hover:text-primary-700 transition-colors">
                                    <i class="fas fa-calendar-check w-5 text-center"></i> Riwayat Booking
                                </a>
                                <a href="{{ route('member.notifikasi.index') }}" class="flex items-center justify-between px-4 py-2.5 text-sm text-dark-700 hover:bg-primary-50 hover:text-primary-700 transition-colors">
                                    <div class="flex items-center gap-3"><i class="fas fa-bell w-5 text-center"></i> Notifikasi</div>
                                    @if($unreadCount > 0)<span class="bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-md font-bold">{{ $unreadCount }}</span>@endif
                                </a>
                                <a href="{{ route('member.profil.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-dark-700 hover:bg-primary-50 hover:text-primary-700 transition-colors border-b border-dark-50">
                                    <i class="fas fa-user-cog w-5 text-center"></i> Pengaturan Profil
                                </a>
                                <form action="{{ route('logout') }}" method="POST" class="px-2 mt-2">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-3 w-full px-2 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors text-left font-medium">
                                        <i class="fas fa-sign-out-alt w-5 text-center"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('admin.dashboard') }}" class="btn-primary">Panel {{ ucfirst(auth()->user()->role->nama) }}</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="font-bold text-dark-700 hover:text-primary-600 px-4 transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
                @endauth
            </div>

            {{-- Mobile Menu Button --}}
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-2xl text-dark-800 focus:outline-none">
                <i class="fas" :class="mobileMenuOpen ? 'fa-times' : 'fa-bars'"></i>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="mobileMenuOpen" x-transition style="display: none;" class="md:hidden absolute top-full left-0 w-full bg-white border-b border-dark-100 shadow-xl py-4 px-6 flex flex-col gap-4">
        <a href="{{ route('home') }}" class="font-bold text-dark-800 text-lg py-2 border-b border-dark-50">Beranda</a>
        <a href="{{ route('lapangan.index') }}" class="font-bold text-dark-800 text-lg py-2 border-b border-dark-50">Cari Lapangan</a>
        <a href="{{ route('leaderboard.index') }}" class="font-bold text-amber-500 text-lg py-2 border-b border-dark-50">Leaderboard</a>
        
        @auth
            @if(auth()->user()->role->slug === 'member')
                <div class="pt-2">
                    <p class="text-xs font-bold text-dark-400 uppercase tracking-widest mb-3">Akun Saya</p>
                    <a href="{{ route('member.dashboard') }}" class="block font-medium text-dark-600 py-2"><i class="fas fa-home w-6"></i> Dashboard</a>
                    <a href="{{ route('member.booking.index') }}" class="block font-medium text-dark-600 py-2"><i class="fas fa-calendar-check w-6"></i> Riwayat Booking</a>
                    <a href="{{ route('member.notifikasi.index') }}" class="block font-medium text-dark-600 py-2"><i class="fas fa-bell w-6"></i> Notifikasi</a>
                    <form action="{{ route('logout') }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="btn-secondary w-full border-red-200 text-red-600 bg-red-50">Keluar</button>
                    </form>
                </div>
            @else
                <a href="{{ route('admin.dashboard') }}" class="btn-primary w-full text-center mt-2">Masuk Panel {{ ucfirst(auth()->user()->role->nama) }}</a>
            @endif
        @else
            <div class="flex gap-4 mt-2">
                <a href="{{ route('login') }}" class="btn-secondary flex-1 text-center">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary flex-1 text-center">Daftar</a>
            </div>
        @endauth
    </div>
</nav>

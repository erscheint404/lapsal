<aside class="absolute z-50 flex flex-col w-64 h-screen px-4 py-8 overflow-y-auto bg-dark-900 border-r rtl:border-r-0 rtl:border-l border-dark-800 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    
    {{-- Logo --}}
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-2 mb-8 group">
        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center text-white transform group-hover:rotate-12 transition-transform shadow-lg shadow-emerald-500/20">
            <i class="fas fa-futbol text-xl"></i>
        </div>
        <div>
            <span class="font-display font-black text-xl tracking-tight text-white block leading-tight">Admin Panel</span>
            <span class="text-xs text-emerald-400 font-medium">{{ \App\Models\Pengaturan::getValue('nama_web', 'Lapsal') }}</span>
        </div>
    </a>

    {{-- Navigation --}}
    <div class="flex flex-col justify-between flex-1 mt-6">
        <nav class="space-y-2">
            
            <a class="flex items-center px-4 py-3 text-dark-300 transition-colors rounded-xl hover:text-white hover:bg-dark-800 {{ request()->routeIs('admin.dashboard') ? 'bg-primary-500/10 text-primary-400 font-medium' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-chart-pie w-5"></i>
                <span class="mx-2 font-medium">Dashboard</span>
            </a>

            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-bold tracking-wider text-dark-500 uppercase">Manajemen</p>
            </div>

            <a class="flex items-center px-4 py-3 text-dark-300 transition-colors rounded-xl hover:text-white hover:bg-dark-800 {{ request()->routeIs('admin.booking.*') ? 'bg-primary-500/10 text-primary-400 font-medium' : '' }}" href="{{ route('admin.booking.index') }}">
                <i class="fas fa-calendar-check w-5"></i>
                <span class="mx-2 font-medium">Data Booking</span>
                @php $pendingCount = \App\Models\Booking::whereIn('status', ['waiting_confirmation'])->count(); @endphp
                @if($pendingCount > 0)
                <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
                @endif
            </a>

            <a class="flex items-center px-4 py-3 text-dark-300 transition-colors rounded-xl hover:text-white hover:bg-dark-800 {{ request()->routeIs('admin.pembayaran.*') ? 'bg-primary-500/10 text-primary-400 font-medium' : '' }}" href="{{ route('admin.pembayaran.index') }}">
                <i class="fas fa-wallet w-5"></i>
                <span class="mx-2 font-medium">Verifikasi Bayar</span>
                @php $reviewCount = \App\Models\Booking::where('status', 'under_review')->count(); @endphp
                @if($reviewCount > 0)
                <span class="ml-auto bg-amber-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $reviewCount }}</span>
                @endif
            </a>

            <a class="flex items-center px-4 py-3 text-dark-300 transition-colors rounded-xl hover:text-white hover:bg-dark-800 {{ request()->routeIs('admin.lapangan.*') ? 'bg-primary-500/10 text-primary-400 font-medium' : '' }}" href="{{ route('admin.lapangan.index') }}">
                <i class="fas fa-layer-group w-5"></i>
                <span class="mx-2 font-medium">Data Lapangan</span>
            </a>

            <a class="flex items-center px-4 py-3 text-dark-300 transition-colors rounded-xl hover:text-white hover:bg-dark-800 {{ request()->routeIs('admin.slot.*') ? 'bg-primary-500/10 text-primary-400 font-medium' : '' }}" href="{{ route('admin.slot.index') }}">
                <i class="fas fa-clock w-5"></i>
                <span class="mx-2 font-medium">Slot Waktu</span>
            </a>

            <a class="flex items-center px-4 py-3 text-dark-300 transition-colors rounded-xl hover:text-white hover:bg-dark-800 {{ request()->routeIs('admin.qrscan.*') ? 'bg-primary-500/10 text-primary-400 font-medium' : '' }}" href="{{ route('admin.qrscan.index') }}">
                <i class="fas fa-qrcode w-5"></i>
                <span class="mx-2 font-medium">QR Scanner</span>
            </a>

            @if(auth()->user()->role->slug === 'admin')
            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-bold tracking-wider text-dark-500 uppercase">Laporan & Admin</p>
            </div>

            <a class="flex items-center px-4 py-3 text-dark-300 transition-colors rounded-xl hover:text-white hover:bg-dark-800 {{ request()->routeIs('admin.laporan.penyewaan') ? 'bg-primary-500/10 text-primary-400 font-medium' : '' }}" href="{{ route('admin.laporan.penyewaan') }}">
                <i class="fas fa-file-alt w-5"></i>
                <span class="mx-2 font-medium">Lap. Penyewaan</span>
            </a>

            <a class="flex items-center px-4 py-3 text-dark-300 transition-colors rounded-xl hover:text-white hover:bg-dark-800 {{ request()->routeIs('admin.laporan.pendapatan') ? 'bg-primary-500/10 text-primary-400 font-medium' : '' }}" href="{{ route('admin.laporan.pendapatan') }}">
                <i class="fas fa-chart-line w-5"></i>
                <span class="mx-2 font-medium">Lap. Pendapatan</span>
            </a>

            <a class="flex items-center px-4 py-3 text-dark-300 transition-colors rounded-xl hover:text-white hover:bg-dark-800 {{ request()->routeIs('admin.user.*') ? 'bg-primary-500/10 text-primary-400 font-medium' : '' }}" href="{{ route('admin.user.index') }}">
                <i class="fas fa-users w-5"></i>
                <span class="mx-2 font-medium">Manajemen User</span>
            </a>

            <a class="flex items-center px-4 py-3 text-dark-300 transition-colors rounded-xl hover:text-white hover:bg-dark-800 {{ request()->routeIs('admin.pengaturan.*') ? 'bg-primary-500/10 text-primary-400 font-medium' : '' }}" href="{{ route('admin.pengaturan.index') }}">
                <i class="fas fa-cog w-5"></i>
                <span class="mx-2 font-medium">Pengaturan Web</span>
            </a>
            @endif
        </nav>

        {{-- Bottom Action --}}
        <div class="mt-8">
            <a href="{{ route('home') }}" target="_blank" class="flex items-center px-4 py-3 text-dark-300 transition-colors rounded-xl hover:text-white hover:bg-dark-800 border border-dark-800">
                <i class="fas fa-external-link-alt w-5"></i>
                <span class="mx-2 font-medium">Lihat Website</span>
            </a>
        </div>
    </div>
</aside>

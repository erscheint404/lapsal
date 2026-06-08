<aside class="fixed z-50 flex flex-col w-64 h-screen px-4 py-6 overflow-y-auto border-r transition-transform duration-300 ease-in-out lg:static lg:translate-x-0"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       style="background: linear-gradient(180deg, #0f172a 0%, #020617 100%); border-right-color: rgba(255,255,255,0.06);">

    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 mb-8 group">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white transform group-hover:scale-110 group-hover:-rotate-6 transition-all duration-300 shadow-lg shrink-0"
             style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 4px 20px rgba(16,185,129,0.25);">
            <i class="fas fa-futbol text-xl"></i>
        </div>
        <div>
            <span class="font-display font-black text-lg tracking-tight text-white block leading-tight">Admin Panel</span>
            <span class="text-xs font-semibold" style="color: #10b981;">{{ \App\Models\Pengaturan::getValue('nama_web', 'Lapsal') }}</span>
        </div>
    </a>

    <div class="flex flex-col justify-between flex-1 mt-4">
        <nav class="space-y-1.5">
            <a class="flex items-center gap-3 px-4 py-3 transition-all rounded-xl {{ request()->routeIs('admin.dashboard') ? 'font-bold' : 'font-medium text-secondary-300 hover:text-white hover:bg-white/5' }}"
               style="{{ request()->routeIs('admin.dashboard') ? 'background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2);' : 'border: 1px solid transparent;' }}"
               href="{{ route('admin.dashboard') }}">
                <i class="fas fa-chart-pie w-5 text-center"></i>
                <span>Dashboard</span>
            </a>

            <div class="pt-6 pb-2">
                <p class="px-4 text-[10px] font-bold tracking-widest uppercase" style="color: #64748b;">Manajemen</p>
            </div>

            <a class="flex items-center gap-3 px-4 py-3 transition-all rounded-xl {{ request()->routeIs('admin.booking.*') ? 'font-bold' : 'font-medium text-secondary-300 hover:text-white hover:bg-white/5' }}"
               style="{{ request()->routeIs('admin.booking.*') ? 'background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2);' : 'border: 1px solid transparent;' }}"
               href="{{ route('admin.booking.index') }}">
                <i class="fas fa-calendar-check w-5 text-center"></i>
                <span>Data Booking</span>
                @php $pendingCount = \App\Models\Booking::whereIn('status', ['waiting_confirmation'])->count(); @endphp
                @if($pendingCount > 0)
                <span class="ml-auto text-white text-[10px] font-black px-2 py-0.5 rounded-md" style="background: #10b981;">{{ $pendingCount }}</span>
                @endif
            </a>

            <a class="flex items-center gap-3 px-4 py-3 transition-all rounded-xl {{ request()->routeIs('admin.pembayaran.*') ? 'font-bold' : 'font-medium text-secondary-300 hover:text-white hover:bg-white/5' }}"
               style="{{ request()->routeIs('admin.pembayaran.*') ? 'background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2);' : 'border: 1px solid transparent;' }}"
               href="{{ route('admin.pembayaran.index') }}">
                <i class="fas fa-wallet w-5 text-center"></i>
                <span>Verifikasi Bayar</span>
                @php $reviewCount = \App\Models\Booking::where('status', 'under_review')->count(); @endphp
                @if($reviewCount > 0)
                <span class="ml-auto text-white text-[10px] font-black px-2 py-0.5 rounded-md" style="background: #10b981;">{{ $reviewCount }}</span>
                @endif
            </a>

            <a class="flex items-center gap-3 px-4 py-3 transition-all rounded-xl {{ request()->routeIs('admin.lapangan.*') ? 'font-bold' : 'font-medium text-secondary-300 hover:text-white hover:bg-white/5' }}"
               style="{{ request()->routeIs('admin.lapangan.*') ? 'background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2);' : 'border: 1px solid transparent;' }}"
               href="{{ route('admin.lapangan.index') }}">
                <i class="fas fa-layer-group w-5 text-center"></i>
                <span>Data Lapangan</span>
            </a>

            <a class="flex items-center gap-3 px-4 py-3 transition-all rounded-xl {{ request()->routeIs('admin.slot.*') ? 'font-bold' : 'font-medium text-secondary-300 hover:text-white hover:bg-white/5' }}"
               style="{{ request()->routeIs('admin.slot.*') ? 'background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2);' : 'border: 1px solid transparent;' }}"
               href="{{ route('admin.slot.index') }}">
                <i class="fas fa-clock w-5 text-center"></i>
                <span>Slot Waktu</span>
            </a>

            <a class="flex items-center gap-3 px-4 py-3 transition-all rounded-xl {{ request()->routeIs('admin.qrscan.*') ? 'font-bold' : 'font-medium text-secondary-300 hover:text-white hover:bg-white/5' }}"
               style="{{ request()->routeIs('admin.qrscan.*') ? 'background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2);' : 'border: 1px solid transparent;' }}"
               href="{{ route('admin.qrscan.index') }}">
                <i class="fas fa-qrcode w-5 text-center"></i>
                <span>QR Scanner</span>
            </a>

            <a class="flex items-center gap-3 px-4 py-3 transition-all rounded-xl {{ request()->routeIs('admin.leaderboard.*') ? 'font-bold' : 'font-medium text-secondary-300 hover:text-white hover:bg-white/5' }}"
               style="{{ request()->routeIs('admin.leaderboard.*') ? 'background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2);' : 'border: 1px solid transparent;' }}"
               href="{{ route('admin.leaderboard.index') }}">
                <i class="fas fa-trophy w-5 text-center"></i>
                <span>Leaderboard</span>
            </a>

            @if(auth()->user()->role->slug === 'admin')
            <div class="pt-6 pb-2">
                <p class="px-4 text-[10px] font-bold tracking-widest uppercase" style="color: #64748b;">Laporan & Admin</p>
            </div>

            <a class="flex items-center gap-3 px-4 py-3 transition-all rounded-xl {{ request()->routeIs('admin.laporan.penyewaan') ? 'font-bold' : 'font-medium text-secondary-300 hover:text-white hover:bg-white/5' }}"
               style="{{ request()->routeIs('admin.laporan.penyewaan') ? 'background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2);' : 'border: 1px solid transparent;' }}"
               href="{{ route('admin.laporan.penyewaan') }}">
                <i class="fas fa-file-alt w-5 text-center"></i>
                <span>Lap. Penyewaan</span>
            </a>

            <a class="flex items-center gap-3 px-4 py-3 transition-all rounded-xl {{ request()->routeIs('admin.laporan.pendapatan') ? 'font-bold' : 'font-medium text-secondary-300 hover:text-white hover:bg-white/5' }}"
               style="{{ request()->routeIs('admin.laporan.pendapatan') ? 'background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2);' : 'border: 1px solid transparent;' }}"
               href="{{ route('admin.laporan.pendapatan') }}">
                <i class="fas fa-chart-line w-5 text-center"></i>
                <span>Lap. Pendapatan</span>
            </a>

            <a class="flex items-center gap-3 px-4 py-3 transition-all rounded-xl {{ request()->routeIs('admin.user.*') ? 'font-bold' : 'font-medium text-secondary-300 hover:text-white hover:bg-white/5' }}"
               style="{{ request()->routeIs('admin.user.*') ? 'background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2);' : 'border: 1px solid transparent;' }}"
               href="{{ route('admin.user.index') }}">
                <i class="fas fa-users w-5 text-center"></i>
                <span>Manajemen User</span>
            </a>

            <a class="flex items-center gap-3 px-4 py-3 transition-all rounded-xl {{ request()->routeIs('admin.pengaturan.*') ? 'font-bold' : 'font-medium text-secondary-300 hover:text-white hover:bg-white/5' }}"
               style="{{ request()->routeIs('admin.pengaturan.*') ? 'background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2);' : 'border: 1px solid transparent;' }}"
               href="{{ route('admin.pengaturan.index') }}">
                <i class="fas fa-cog w-5 text-center"></i>
                <span>Pengaturan Web</span>
            </a>
            @endif
        </nav>

        <div class="mt-8">
            <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-4 py-3 text-secondary-300 transition-all rounded-xl hover:text-white hover:bg-white/5 mb-2 font-medium"
               style="border: 1px solid rgba(255,255,255,0.1);">
                <i class="fas fa-external-link-alt w-5 text-center"></i>
                <span>Lihat Website</span>
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-4 py-3 text-red-400 transition-all rounded-xl hover:text-red-300 hover:bg-red-500/10 w-full font-medium"
                        style="border: 1px solid transparent;">
                    <i class="fas fa-sign-out-alt w-5 text-center"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </div>
</aside>

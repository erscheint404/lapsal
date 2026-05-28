<header class="sticky top-0 bg-white border-b border-dark-100 z-30">
    <div class="flex items-center justify-between px-6 py-4">
        
        {{-- Mobile Menu Button --}}
        <div class="flex items-center gap-4">
            <button @click="sidebarOpen = true" class="text-dark-500 hover:text-dark-900 focus:outline-none lg:hidden">
                <i class="fas fa-bars text-xl"></i>
            </button>
            
            {{-- Search Bar (Optional/Decorative for now) --}}
            <div class="hidden md:flex relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-dark-400">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" class="w-64 py-2 pl-10 pr-4 text-sm bg-dark-50 border border-dark-100 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors" placeholder="Cari data...">
            </div>
        </div>

        {{-- Right Topbar --}}
        <div class="flex items-center gap-4">
            
            {{-- Notifications --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" class="relative p-2 text-dark-500 hover:text-dark-900 transition-colors rounded-full hover:bg-dark-50">
                    <i class="far fa-bell text-xl"></i>
                    <span class="absolute top-1.5 right-2 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
                </button>
                
                {{-- Dropdown Notif --}}
                <div x-show="open" x-transition style="display: none;" class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-dark-100 overflow-hidden z-50">
                    <div class="p-4 border-b border-dark-100 flex justify-between items-center">
                        <h3 class="font-bold text-dark-900">Notifikasi Terbaru</h3>
                    </div>
                    <div class="max-h-80 overflow-y-auto">
                        <div class="p-4 text-center text-sm text-dark-500 border-b border-dark-50">
                            Fitur notifikasi admin akan segera hadir.
                        </div>
                    </div>
                </div>
            </div>

            <div class="h-8 w-px bg-dark-200"></div>

            {{-- User Dropdown --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 focus:outline-none hover:bg-dark-50 p-1.5 rounded-xl transition-colors">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-dark-900 leading-none">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-dark-500 mt-1 capitalize">{{ auth()->user()->role->nama }}</p>
                    </div>
                    <img src="{{ auth()->user()->avatar_url }}" class="w-9 h-9 rounded-full object-cover border border-dark-200">
                    <i class="fas fa-chevron-down text-xs text-dark-400 ml-1"></i>
                </button>
                
                {{-- Dropdown --}}
                <div x-show="open" x-transition.opacity.duration.200ms style="display: none;" 
                     class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-dark-100 overflow-hidden z-50 py-2">
                    
                    <form action="{{ route('logout') }}" method="POST" class="px-2 mt-1">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 w-full px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors text-left font-medium">
                            <i class="fas fa-sign-out-alt w-4 text-center"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</header>

<header class="sticky top-0 z-30 transition-all duration-300"
        style="background: rgba(255,255,255,0.85); backdrop-filter: blur(20px) saturate(180%); border-bottom: 1px solid rgba(0,0,0,0.06);">
    <div class="flex items-center justify-between px-6 lg:px-8 py-3.5">

        <div class="flex items-center gap-4">
            <button @click="sidebarOpen = true" class="w-10 h-10 flex items-center justify-center rounded-xl text-dark-500 hover:text-dark-900 transition-colors lg:hidden"
                    style="background: rgba(0,0,0,0.04);">
                <i class="fas fa-bars text-lg"></i>
            </button>

            <div class="hidden md:flex relative group">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-dark-400 group-focus-within:text-primary-600 transition-colors">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" class="w-64 py-2.5 pl-11 pr-4 text-sm rounded-2xl transition-all"
                       style="background: rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.06);"
                       onfocus="this.style.background='white';this.style.borderColor='rgba(204,255,0,0.5)';this.style.boxShadow='0 0 0 4px rgba(204,255,0,0.1)'"
                       onblur="this.style.background='rgba(0,0,0,0.03)';this.style.borderColor='rgba(0,0,0,0.06)';this.style.boxShadow='none'"
                       placeholder="Cari data...">
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" class="relative w-10 h-10 flex items-center justify-center rounded-xl text-dark-500 hover:text-dark-900 transition-colors"
                        style="background: rgba(0,0,0,0.04);">
                    <i class="far fa-bell text-lg"></i>
                    <span class="absolute top-2.5 right-2.5 w-2.5 h-2.5 rounded-full ring-2 ring-white" style="background: #00e5ff;"></span>
                </button>

                <div x-show="open" x-transition class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-2xl overflow-hidden z-50"
                     style="display: none; border: 1px solid rgba(0,0,0,0.08);">
                    <div class="p-4 flex justify-between items-center" style="border-bottom: 1px solid rgba(0,0,0,0.06); background: rgba(0,0,0,0.02);">
                        <h3 class="font-bold text-dark-900">Notifikasi</h3>
                    </div>
                    <div class="max-h-80 overflow-y-auto">
                        <div class="p-8 text-center text-sm text-dark-400">
                            <i class="far fa-bell-slash text-2xl mb-3 opacity-50"></i>
                            <p>Belum ada notifikasi.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-px h-8 mx-1" style="background: rgba(0,0,0,0.08);"></div>

            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 focus:outline-none p-1.5 pr-3 rounded-2xl transition-all"
                        style="border: 1px solid transparent;"
                        onmouseover="this.style.background='rgba(0,0,0,0.03)';this.style.borderColor='rgba(0,0,0,0.06)'"
                        onmouseout="this.style.background='transparent';this.style.borderColor='transparent'">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-dark-900 leading-none">{{ auth()->user()->name }}</p>
                        <p class="text-xs mt-1 capitalize font-semibold" style="color: #6e8f00;">{{ auth()->user()->role->nama }}</p>
                    </div>
                    <img src="{{ auth()->user()->avatar_url }}" class="w-10 h-10 rounded-xl object-cover shadow-sm shrink-0" style="border: 2px solid rgba(204,255,0,0.3);">
                    <i class="fas fa-chevron-down text-xs text-dark-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open" x-transition.opacity.duration.200ms
                     class="absolute right-0 mt-3 w-48 bg-white rounded-2xl shadow-2xl overflow-hidden z-50 py-2"
                     style="display: none; border: 1px solid rgba(0,0,0,0.08);">

                    <div class="px-4 py-3" style="border-bottom: 1px solid rgba(0,0,0,0.06); background: rgba(0,0,0,0.02);">
                        <p class="font-bold text-dark-900 text-sm">{{ auth()->user()->name }}</p>
                        <p class="text-xs capitalize font-semibold" style="color: #6e8f00;">{{ auth()->user()->role->nama }}</p>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="px-2 mt-2">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 w-full px-3 py-2.5 text-sm rounded-xl transition-colors text-left font-bold"
                                style="color: #ef4444;"
                                onmouseover="this.style.background='rgba(239,68,68,0.08)'"
                                onmouseout="this.style.background='transparent'">
                            <i class="fas fa-sign-out-alt w-4 text-center"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

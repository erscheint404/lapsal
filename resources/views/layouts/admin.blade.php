<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') | {{ \App\Models\Pengaturan::getValue('nama_web', 'Lapsal') }}</title>
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    
    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- AlpineJS --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="font-sans text-dark-700 bg-dark-50 antialiased" x-data="{ sidebarOpen: false }">
    
    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        @include('components.admin-sidebar')

        {{-- Main Content Wrapper --}}
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
            
            {{-- Topbar --}}
            @include('components.admin-topbar')

            {{-- Main Content --}}
            <main class="w-full grow p-6">
                {{-- Page Header --}}
                <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-dark-900">@yield('page_title', 'Dashboard')</h1>
                        @hasSection('page_description')
                            <p class="text-sm text-dark-500 mt-1">@yield('page_description')</p>
                        @endif
                    </div>
                    @yield('page_actions')
                </div>

                {{-- Content --}}
                @yield('content')
            </main>
            
            <footer class="py-4 text-center text-sm text-dark-500 border-t border-dark-200 mt-auto bg-white">
                &copy; {{ date('Y') }} {{ \App\Models\Pengaturan::getValue('nama_web', 'Lapsal') }}. All rights reserved.
            </footer>
        </div>
    </div>

    {{-- Overlay for mobile sidebar --}}
    <div x-show="sidebarOpen" x-transition.opacity style="display: none;" 
         @click="sidebarOpen = false"
         class="fixed inset-0 z-40 bg-dark-900/50 backdrop-blur-sm lg:hidden">
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
         class="fixed bottom-4 right-4 bg-emerald-500 text-white px-6 py-4 rounded-xl shadow-xl flex items-center gap-3 z-50 transition-all duration-300"
         x-transition:enter="transform ease-out duration-300 transition" x-transition:enter-start="translate-y-10 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-10 opacity-0">
        <i class="fas fa-check-circle text-xl"></i>
        <p class="font-medium">{{ session('success') }}</p>
        <button @click="show = false" class="ml-4 text-emerald-200 hover:text-white"><i class="fas fa-times"></i></button>
    </div>
    @endif
    
    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
         class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-4 rounded-xl shadow-xl flex items-center gap-3 z-50 transition-all duration-300"
         x-transition:enter="transform ease-out duration-300 transition" x-transition:enter-start="translate-y-10 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-10 opacity-0">
        <i class="fas fa-exclamation-circle text-xl"></i>
        <p class="font-medium">{{ session('error') }}</p>
        <button @click="show = false" class="ml-4 text-red-200 hover:text-white"><i class="fas fa-times"></i></button>
    </div>
    @endif

    @stack('scripts')
</body>
</html>

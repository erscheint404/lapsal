<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') | {{ \App\Models\Pengaturan::getValue('nama_web', 'Lapsal') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400&display=swap" rel="stylesheet">
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@400,500,600,700,800,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0f1b2e">

    @stack('styles')
</head>
<body class="font-sans text-dark-700 bg-dark-50 antialiased" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        @include('components.admin-sidebar')

        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">

            @include('components.admin-topbar')

            <main class="w-full grow p-6 lg:p-8">
                <div class="mb-8">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-3xl lg:text-4xl font-display font-bold text-dark-900 tracking-tight">@yield('page_title', 'Dashboard')</h1>
                            @hasSection('page_description')
                                <p class="text-dark-500 mt-1.5">@yield('page_description')</p>
                            @endif
                        </div>
                        @yield('page_actions')
                    </div>
                </div>

                @yield('content')
            </main>

            <footer class="py-5 text-center text-sm text-dark-400 mt-auto" style="border-top: 1px solid rgba(0,0,0,0.06); background: rgba(255,255,255,0.5); backdrop-filter: blur(10px);">
                &copy; {{ date('Y') }} {{ \App\Models\Pengaturan::getValue('nama_web', 'Lapsal') }}. All rights reserved.
            </footer>
        </div>
    </div>

    <div x-show="sidebarOpen" x-transition.opacity style="display: none;"
         @click="sidebarOpen = false"
         class="fixed inset-0 z-40 lg:hidden" style="background: rgba(10,18,33,0.6); backdrop-filter: blur(4px);">
    </div>

    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         class="fixed bottom-6 right-6 px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 z-50 backdrop-blur-xl"
         style="background: rgba(255,255,255,0.92); border: 1px solid rgba(204,255,0,0.3);"
         x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-8 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-8 opacity-0">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background: rgba(204,255,0,0.15);">
            <i class="fas fa-check-circle text-lg" style="color: #6e8f00;"></i>
        </div>
        <p class="font-semibold text-sm text-dark-900">{{ session('success') }}</p>
        <button @click="show = false" class="ml-2 p-1.5 rounded-lg hover:bg-dark-100 transition-colors text-dark-400">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         class="fixed bottom-6 right-6 bg-white text-dark-900 px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 z-50 border border-red-100/80 backdrop-blur-xl"
         x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-8 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-8 opacity-0">
        <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center shrink-0">
            <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
        </div>
        <p class="font-semibold text-sm">{{ session('error') }}</p>
        <button @click="show = false" class="ml-2 p-1.5 rounded-lg hover:bg-dark-100 transition-colors text-dark-400">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js').catch(() => {});
        }
    </script>

    @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Booking Lapangan Futsal') | {{ \App\Models\Pengaturan::getValue('nama_web', 'Lapsal') }}</title>
    <meta name="description" content="@yield('meta_description', 'Platform penyewaan lapangan futsal modern, cepat, dan terpercaya. Booking online dalam hitungan menit.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400&display=swap" rel="stylesheet">
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@400,500,600,700,800,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0f1b2e">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Lapsal">
    <link rel="apple-touch-icon" href="/build/assets/pwa-icon-192.png">

    @stack('styles')
</head>
<body class="font-sans text-dark-700 bg-dark-50 antialiased flex flex-col min-h-screen">

    @include('components.navbar')

    <main class="flex-grow">
        @yield('content')
    </main>

    @include('components.footer')

    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         class="fixed bottom-6 right-6 px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 z-50 backdrop-blur-xl border"
         style="background: rgba(255,255,255,0.92); border-color: rgba(204,255,0,0.3);"
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

    {{-- Scroll Reveal Observer --}}
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js').catch(() => {});
        }

        // Intersection Observer for scroll animations
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

            document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale').forEach(el => {
                observer.observe(el);
            });

            // Counter animation
            document.querySelectorAll('[data-count]').forEach(el => {
                const target = parseInt(el.getAttribute('data-count'));
                const duration = 2000;
                const step = target / (duration / 16);
                let current = 0;

                const counterObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const timer = setInterval(() => {
                                current += step;
                                if (current >= target) {
                                    current = target;
                                    clearInterval(timer);
                                }
                                el.textContent = Math.floor(current).toLocaleString('id-ID');
                            }, 16);
                            counterObserver.unobserve(el);
                        }
                    });
                }, { threshold: 0.5 });
                counterObserver.observe(el);
            });
        });
    </script>

    @stack('scripts')
</body>
</html>

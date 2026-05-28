<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Booking Lapangan Futsal') | {{ \App\Models\Pengaturan::getValue('nama_web', 'Lapsal') }}</title>
    
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
<body class="font-sans text-dark-700 bg-white antialiased flex flex-col min-h-screen">
    
    {{-- Navbar --}}
    @include('components.navbar')

    {{-- Main Content --}}
    <main class="flex-grow pt-20">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('components.footer')

    {{-- Flash Messages --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
         class="fixed bottom-4 right-4 bg-emerald-500 text-white px-6 py-4 rounded-xl shadow-xl flex items-center gap-3 z-50 transition-all duration-300 transform"
         x-transition:enter="translate-y-10 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="translate-y-0 opacity-100" x-transition:leave-end="translate-y-10 opacity-0">
        <i class="fas fa-check-circle text-xl"></i>
        <p class="font-medium">{{ session('success') }}</p>
        <button @click="show = false" class="ml-4 text-emerald-200 hover:text-white"><i class="fas fa-times"></i></button>
    </div>
    @endif
    
    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
         class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-4 rounded-xl shadow-xl flex items-center gap-3 z-50 transition-all duration-300 transform"
         x-transition:enter="translate-y-10 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="translate-y-0 opacity-100" x-transition:leave-end="translate-y-10 opacity-0">
        <i class="fas fa-exclamation-circle text-xl"></i>
        <p class="font-medium">{{ session('error') }}</p>
        <button @click="show = false" class="ml-4 text-red-200 hover:text-white"><i class="fas fa-times"></i></button>
    </div>
    @endif

    @stack('scripts')
</body>
</html>

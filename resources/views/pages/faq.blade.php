@extends('layouts.app')
@section('title', 'FAQ')
@section('content')
<section class="section-dark pt-32 pb-16 relative">
    <div class="absolute inset-0 dot-pattern opacity-20"></div>
    <div class="container-custom relative z-10">
        <div class="text-center max-w-3xl mx-auto">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-6" style="background: rgba(0,229,255,0.1); border: 1px solid rgba(0,229,255,0.2);">
                <i class="fas fa-question-circle text-3xl" style="color: #00e5ff;"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-display font-bold text-white tracking-tight mb-4">Pertanyaan Umum</h1>
            <p class="text-dark-300 text-lg leading-relaxed">Temukan jawaban untuk pertanyaan yang sering diajukan.</p>
        </div>
    </div>
</section>

<section class="py-16 bg-dark-50 -mt-1">
    <div class="container-custom">
        <div class="max-w-3xl mx-auto space-y-4" x-data="{ active: null }">
            @php
            $faqs = [
                ['q' => 'Bagaimana cara booking lapangan?', 'a' => 'Pilih lapangan yang diinginkan, tentukan tanggal dan slot waktu, lalu selesaikan pembayaran. Setelah pembayaran terkonfirmasi, kamu akan menerima QR Code sebagai tiket masuk.', 'icon' => 'fa-calendar-check'],
                ['q' => 'Metode pembayaran apa saja yang tersedia?', 'a' => 'Kami menerima pembayaran melalui Midtrans (transfer bank, e-wallet, kartu kredit) dan juga upload bukti transfer manual sebagai opsi cadangan.', 'icon' => 'fa-credit-card'],
                ['q' => 'Apakah bisa booking untuk lebih dari 1 jam?', 'a' => 'Tentu! Kamu bisa memilih beberapa slot waktu berurutan untuk durasi yang lebih panjang. Harga akan dihitung otomatis berdasarkan total jam yang dipilih.', 'icon' => 'fa-clock'],
                ['q' => 'Bagaimana jika saya ingin membatalkan booking?', 'a' => 'Pembatalan dapat dilakukan melalui halaman detail booking. Slot yang dibatalkan akan dikembalikan ke ketersediaan umum. Biaya pembatalan berlaku sesuai ketentuan.', 'icon' => 'fa-times-circle'],
                ['q' => 'Apa itu QR Code dan bagaimana cara pakainya?', 'a' => 'QR Code adalah tiket digitalmu. Setelah booking dikonfirmasi, kamu akan mendapatkan QR Code unik. Tunjukkan QR Code ini kepada petugas di lokasi untuk validasi kehadiran.', 'icon' => 'fa-qrcode'],
                ['q' => 'Apakah saya bisa memberikan rating setelah bermain?', 'a' => 'Ya! Setelah sesi booking selesai, kamu bisa memberikan rating bintang 1-5 dan ulasan singkat tentang lapangan. Rating ini membantu member lain memilih lapangan terbaik.', 'icon' => 'fa-star'],
                ['q' => 'Bagaimana cara melihat leaderboard?', 'a' => 'Leaderboard bisa diakses oleh siapa saja melalui menu Leaderboard di navigasi utama. Statistik gol diupdate setelah petugas mencatat hasil pertandingan.', 'icon' => 'fa-trophy'],
                ['q' => 'Apakah ada sanksi jika booking tidak digunakan?', 'a' => 'Booking yang sudah dikonfirmasi tetapi tidak digunakan tanpa pembatalan akan tetap tercatat. Kami mendorong untuk membatalkan tepat waktu jika tidak bisa hadir agar slot bisa digunakan member lain.', 'icon' => 'fa-exclamation-triangle'],
            ];
            @endphp

            @foreach($faqs as $i => $faq)
            <div class="card-premium overflow-hidden transition-all duration-300 reveal" style="transition-delay: {{ $i * 60 }}ms;"
                 :class="active === {{ $i }} ? 'shadow-lg' : ''">
                <button @click="active = active === {{ $i }} ? null : {{ $i }}"
                        class="w-full flex items-center gap-4 px-6 py-5 text-left focus:outline-none">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors duration-300"
                         :style="active === {{ $i }} ? 'background: rgba(204,255,0,0.12); color: #6e8f00;' : 'background: rgba(0,0,0,0.03); color: #627d9e;'">
                        <i class="fas {{ $faq['icon'] }}"></i>
                    </div>
                    <span class="font-bold text-dark-900 text-lg pr-4 flex-1">{{ $faq['q'] }}</span>
                    <i class="fas fa-chevron-down text-dark-400 transition-transform duration-300 shrink-0"
                       :class="active === {{ $i }} ? 'rotate-180' : ''"
                       :style="active === {{ $i }} ? 'color: #6e8f00;' : ''"></i>
                </button>
                <div x-show="active === {{ $i }}" x-collapse>
                    <div class="px-6 pb-6 pl-20">
                        <p class="text-dark-600 leading-relaxed">{{ $faq['a'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-16 p-10 rounded-3xl max-w-2xl mx-auto reveal" style="background: linear-gradient(135deg, rgba(204,255,0,0.05), rgba(0,229,255,0.05)); border: 1px solid rgba(204,255,0,0.1);">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background: rgba(204,255,0,0.1);">
                <i class="fas fa-headset text-2xl" style="color: #6e8f00;"></i>
            </div>
            <h3 class="text-xl font-bold text-dark-900 mb-2">Masih Punya Pertanyaan?</h3>
            <p class="text-dark-500 mb-6">Tim kami siap membantu Anda. Hubungi kami melalui WhatsApp atau email.</p>
            <a href="{{ route('kontak') }}" class="btn-primary">Hubungi Kami</a>
        </div>
    </div>
</section>
@endsection

@extends('layouts.admin')
@section('title', 'QR Scanner')
@section('page_title', 'Scanner Tiket')
@section('page_description', 'Scan QR Code tiket member untuk verifikasi kehadiran.')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    {{-- Scanner Area --}}
    <div class="card-premium overflow-hidden border-2" style="border-color: rgba(204,255,0,0.3);">
        <div class="p-6 text-center text-white relative overflow-hidden" style="background: linear-gradient(135deg, #0a1221, #0f1b2e);">
            <div class="absolute inset-0 dot-pattern opacity-10"></div>
            <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full blur-[40px]" style="background: rgba(204,255,0,0.15);"></div>
            <div class="relative z-10">
                <i class="fas fa-qrcode text-4xl mb-3" style="color: #ccff00;"></i>
                <h3 class="text-xl font-bold tracking-tight mb-1">Arahkan Kamera ke QR Code</h3>
                <p class="text-sm" style="color: rgba(255,255,255,0.7);">Pastikan kode berada di tengah kotak merah</p>
            </div>
        </div>

        <div class="p-4 bg-dark-900 relative">
            <div id="reader" class="w-full mx-auto overflow-hidden rounded-2xl shadow-inner border border-dark-700 bg-black min-h-[300px]"></div>
            
            {{-- Scanning effect overlay --}}
            <div class="absolute top-4 left-4 right-4 bottom-4 pointer-events-none z-10 flex items-center justify-center">
                <div class="w-48 h-48 border-2 border-dashed rounded-xl relative" style="border-color: rgba(204,255,0,0.5);">
                    <div class="absolute top-0 left-0 w-4 h-4 border-t-4 border-l-4 rounded-tl-xl" style="border-color: #ccff00;"></div>
                    <div class="absolute top-0 right-0 w-4 h-4 border-t-4 border-r-4 rounded-tr-xl" style="border-color: #ccff00;"></div>
                    <div class="absolute bottom-0 left-0 w-4 h-4 border-b-4 border-l-4 rounded-bl-xl" style="border-color: #ccff00;"></div>
                    <div class="absolute bottom-0 right-0 w-4 h-4 border-b-4 border-r-4 rounded-br-xl" style="border-color: #ccff00;"></div>
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-b from-transparent to-primary-500/50 animate-scan"></div>
                </div>
            </div>
        </div>

        <div class="p-6 bg-dark-50 border-t border-dark-100/60">
            <form action="{{ route('admin.qrscan.process') }}" method="POST" id="qr-form" class="flex flex-col sm:flex-row gap-4">
                @csrf
                <div class="flex-1 relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-dark-400"><i class="fas fa-keyboard"></i></span>
                    <input type="text" name="kode_booking" id="kode_booking" class="w-full py-3 pl-11 pr-4 text-sm bg-white border border-dark-200 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm font-mono uppercase font-bold" placeholder="Input kode manual (contoh: LPF-XXXXXXXX)" required>
                </div>
                <button type="submit" class="btn-primary py-3 px-6 shadow-lg shadow-primary-500/20 w-full sm:w-auto">
                    <i class="fas fa-search mr-2"></i>Cek Tiket
                </button>
            </form>
        </div>
    </div>

    {{-- Result Area (if exists) --}}
    @if(session('booking'))
        @php $booking = session('booking'); @endphp
        <div class="space-y-6 reveal-right">
            <div class="card-premium p-8 relative overflow-hidden" style="border: 2px solid #ccff00; background: linear-gradient(135deg, white, #f8fafc);">
                <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full blur-[40px]" style="background: rgba(204,255,0,0.15);"></div>
                
                <div class="flex items-center gap-4 mb-8 relative z-10 pb-6 border-b border-dark-100/60">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center shadow-lg" style="background: #ccff00; color: #0a1221;">
                        <i class="fas fa-check-circle text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-dark-900 tracking-tight leading-none mb-1">Tiket Valid</h3>
                        <p class="font-mono font-bold text-dark-500 uppercase tracking-wider">{{ $booking->kode_booking }}</p>
                    </div>
                </div>

                <div class="space-y-6 relative z-10">
                    <div>
                        <p class="text-xs font-bold text-dark-400 uppercase tracking-wider mb-1">Pemesan</p>
                        <div class="flex items-center gap-3">
                            <img src="{{ $booking->user->avatar_url }}" class="w-10 h-10 rounded-full object-cover border border-dark-100 shadow-sm">
                            <div>
                                <p class="font-bold text-dark-900">{{ $booking->user->name }}</p>
                                <p class="text-sm text-dark-500">{{ $booking->user->phone ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <p class="text-xs font-bold text-dark-400 uppercase tracking-wider mb-1">Lapangan & Waktu</p>
                        <div class="bg-dark-50 p-4 rounded-xl border border-dark-100/60 shadow-inner">
                            <p class="font-bold text-dark-900 text-lg mb-2">{{ $booking->lapangan->nama }}</p>
                            <div class="grid grid-cols-2 gap-4 text-sm font-medium">
                                <div class="flex items-center gap-2 text-dark-600"><i class="far fa-calendar-alt" style="color: #6e8f00;"></i> {{ $booking->tanggal->format('d M Y') }}</div>
                                <div class="flex items-center gap-2 text-dark-600"><i class="far fa-clock" style="color: #6e8f00;"></i> {{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-dark-100/60 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-dark-400 uppercase tracking-wider mb-1">Status</p>
                            <span class="badge" style="background: rgba(204,255,0,0.15); color: #526b00; border: 1px solid rgba(204,255,0,0.3);">{{ $booking->status_label }}</span>
                        </div>
                        
                        @if($booking->status === 'confirmed')
                        <form action="{{ route('admin.booking.update-status', $booking->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="btn-primary shadow-lg" onclick="return confirm('Tandai booking ini sebagai selesai?')">
                                <i class="fas fa-flag-checkered mr-2"></i>Tandai Selesai
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @elseif(session('error_scan'))
        <div class="card-premium p-10 text-center flex flex-col justify-center border-2 border-red-200 reveal-right">
            <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 bg-red-100 text-red-500 shadow-inner">
                <i class="fas fa-times-circle text-4xl"></i>
            </div>
            <h3 class="text-2xl font-black text-dark-900 tracking-tight mb-2">Tiket Tidak Valid</h3>
            <p class="text-dark-600">{{ session('error_scan') }}</p>
        </div>
    @else
        <div class="card-premium p-10 text-center flex flex-col justify-center border border-dashed border-dark-200 bg-dark-50/50 h-full">
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-5" style="background: rgba(0,0,0,0.03); color: #627d9e;">
                <i class="fas fa-ticket-alt text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-dark-900 mb-2">Menunggu Scan...</h3>
            <p class="text-dark-500 text-sm">Hasil scan atau pencarian tiket akan muncul di sini.</p>
        </div>
    @endif
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Fix standard CSS conflict for html5-qrcode
        const style = document.createElement('style');
        style.innerHTML = `
            #reader { border: none !important; }
            #reader video { object-fit: cover !important; border-radius: 1rem; }
            #reader__dashboard_section_csr span { color: white !important; font-family: inherit !important; }
            #reader__dashboard_section_csr button { background: #0f1b2e !important; color: white !important; border: 1px solid rgba(255,255,255,0.1) !important; border-radius: 0.5rem !important; padding: 0.5rem 1rem !important; font-weight: 600 !important; cursor: pointer !important; }
            #reader a { color: #ccff00 !important; text-decoration: none !important; }
        `;
        document.head.appendChild(style);

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", { fps: 10, qrbox: {width: 250, height: 250}, aspectRatio: 1.0 }, false);

        function onScanSuccess(decodedText, decodedResult) {
            // Stop scanning and submit form
            html5QrcodeScanner.clear();
            document.getElementById('kode_booking').value = decodedText;
            
            // Show loading state on button
            const submitBtn = document.querySelector('#qr-form button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            
            document.getElementById('qr-form').submit();
        }

        function onScanFailure(error) {
            // handle scan failure, usually better to ignore and keep scanning.
            // console.warn(`Code scan error = ${error}`);
        }

        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    });
</script>

<style>
    @keyframes scan {
        0% { transform: translateY(-100%); }
        50% { transform: translateY(240px); }
        100% { transform: translateY(-100%); }
    }
    .animate-scan {
        animation: scan 3s linear infinite;
    }
</style>
@endpush
@endsection
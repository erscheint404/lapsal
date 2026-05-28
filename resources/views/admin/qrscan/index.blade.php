@extends('layouts.admin')
@section('title', 'QR Scanner')
@section('page_title', 'Scanner Tiket Masuk')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-dark-900 rounded-2xl shadow-xl overflow-hidden relative" x-data="qrScanner()">
        <div class="p-4 bg-dark-950 text-white flex justify-between items-center z-10 relative">
            <h3 class="font-bold"><i class="fas fa-camera mr-2"></i> Scan QR Code</h3>
            <div class="flex gap-2">
                <button @click="switchCamera" class="btn-icon text-white bg-dark-800 hover:bg-dark-700"><i class="fas fa-sync-alt"></i></button>
            </div>
        </div>
        
        <div class="relative aspect-[4/3] bg-black">
            {{-- Camera Video Element --}}
            <video id="qr-video" class="w-full h-full object-cover"></video>
            
            {{-- Scanner Overlay Frame --}}
            <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                <div class="w-64 h-64 border-2 border-emerald-500 rounded-xl relative shadow-[0_0_0_4000px_rgba(0,0,0,0.5)]">
                    <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-emerald-400 rounded-tl-xl"></div>
                    <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-emerald-400 rounded-tr-xl"></div>
                    <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-emerald-400 rounded-bl-xl"></div>
                    <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-emerald-400 rounded-br-xl"></div>
                    
                    {{-- Scanning Line Animation --}}
                    <div class="absolute top-0 left-0 w-full h-1 bg-emerald-400 shadow-[0_0_10px_#34d399] animate-[scan_2s_linear_infinite]"></div>
                </div>
            </div>
            
            <div x-show="loading" class="absolute inset-0 bg-dark-900/80 flex flex-col items-center justify-center text-emerald-400 z-20">
                <i class="fas fa-circle-notch fa-spin text-4xl mb-4"></i>
                <p class="font-bold tracking-widest">MEMVERIFIKASI TIKET...</p>
            </div>
        </div>
        
        <div class="p-4 bg-dark-950 text-center text-dark-400 text-xs">
            Arahkan kamera ke QR Code tiket member
        </div>
    </div>

    {{-- Result Card --}}
    <div id="result-container">
        <div class="bg-white rounded-2xl border border-dark-100 shadow-sm p-8 flex flex-col items-center justify-center h-full text-center min-h-[400px]">
            <div class="w-24 h-24 bg-dark-50 rounded-full flex items-center justify-center text-dark-200 text-4xl mb-4 border-2 border-dashed border-dark-200">
                <i class="fas fa-qrcode"></i>
            </div>
            <h3 class="font-bold text-xl text-dark-900 mb-2">Menunggu Scan</h3>
            <p class="text-dark-500">Hasil verifikasi tiket akan muncul di sini.</p>
        </div>
    </div>
</div>

@push('scripts')
<style>
    @keyframes scan {
        0% { top: 0; opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { top: 100%; opacity: 0; }
    }
</style>
{{-- Using HTML5 QR Code Library --}}
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('qrScanner', () => ({
        scanner: null,
        loading: false,
        cameras: [],
        currentCameraIndex: 0,
        
        init() {
            this.scanner = new Html5Qrcode("qr-video");
            
            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    this.cameras = devices;
                    // Prefer back camera if available (usually index 1 on phones, but we'll try environment facing mode first)
                    this.startScan({ facingMode: "environment" });
                }
            }).catch(err => {
                alert("Tidak dapat mengakses kamera: " + err);
            });
        },
        
        startScan(config) {
            this.scanner.start(
                config, 
                { fps: 10, qrbox: { width: 250, height: 250 } },
                (decodedText, decodedResult) => {
                    this.handleScan(decodedText);
                },
                (errorMessage) => {
                    // Ignore background scan errors
                }
            );
        },
        
        switchCamera() {
            if (this.cameras.length > 1) {
                this.scanner.stop().then(() => {
                    this.currentCameraIndex = (this.currentCameraIndex + 1) % this.cameras.length;
                    this.startScan(this.cameras[this.currentCameraIndex].id);
                });
            }
        },
        
        handleScan(qrCodeData) {
            if (this.loading) return; // Prevent multiple scans
            
            this.loading = true;
            this.scanner.pause(true); // Pause scanning
            
            // Call API backend to validate
            fetch('{{ route("api.admin.qrvalidate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ qr_data: qrCodeData })
            })
            .then(response => response.json())
            .then(data => {
                this.renderResult(data);
            })
            .catch(error => {
                console.error('Error:', error);
                this.renderError();
            })
            .finally(() => {
                setTimeout(() => {
                    this.loading = false;
                    this.scanner.resume(); // Resume after 3 seconds
                }, 3000);
            });
        },
        
        renderResult(data) {
            const container = document.getElementById('result-container');
            
            if (data.valid) {
                const booking = data.booking;
                container.innerHTML = `
                    <div class="bg-emerald-50 rounded-2xl border border-emerald-200 shadow-sm p-6 h-full flex flex-col">
                        <div class="text-center mb-6">
                            <div class="w-20 h-20 bg-emerald-500 rounded-full flex items-center justify-center text-white text-4xl mx-auto mb-4 shadow-lg shadow-emerald-500/30">
                                <i class="fas fa-check"></i>
                            </div>
                            <h3 class="font-black text-2xl text-emerald-800 mb-1">TIKET VALID</h3>
                            <p class="font-mono font-bold text-emerald-600 bg-emerald-100 py-1 px-3 rounded inline-block">${booking.kode_booking}</p>
                        </div>
                        
                        <div class="bg-white rounded-xl p-5 shadow-sm space-y-4 flex-1">
                            <div>
                                <p class="text-xs font-bold text-dark-400 uppercase">Penyewa</p>
                                <p class="font-bold text-dark-900 text-lg">${booking.user_name}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-dark-400 uppercase">Lapangan</p>
                                <p class="font-bold text-primary-700 text-lg">${booking.lapangan_nama}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs font-bold text-dark-400 uppercase">Tanggal</p>
                                    <p class="font-bold text-dark-900">${booking.tanggal}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-dark-400 uppercase">Waktu</p>
                                    <p class="font-bold text-dark-900">${booking.jam_mulai} - ${booking.jam_selesai}</p>
                                </div>
                            </div>
                        </div>
                        
                        <a href="/admin/booking/${booking.id}" class="btn-primary bg-emerald-500 hover:bg-emerald-600 border-none w-full mt-4 text-center">Lihat Detail Booking</a>
                    </div>
                `;
                
                // Play success sound
                new Audio('https://www.soundjay.com/buttons/sounds/button-09.mp3').play().catch(e => {});
            } else {
                container.innerHTML = `
                    <div class="bg-red-50 rounded-2xl border border-red-200 shadow-sm p-8 flex flex-col items-center justify-center h-full text-center">
                        <div class="w-24 h-24 bg-red-500 rounded-full flex items-center justify-center text-white text-5xl mb-6 shadow-lg shadow-red-500/30">
                            <i class="fas fa-times"></i>
                        </div>
                        <h3 class="font-black text-2xl text-red-800 mb-2">TIKET TIDAK VALID</h3>
                        <p class="text-red-600 font-medium">${data.message}</p>
                        
                        <button onclick="document.getElementById('result-container').innerHTML = '<div class=\\'bg-white rounded-2xl border border-dark-100 shadow-sm p-8 flex flex-col items-center justify-center h-full text-center min-h-[400px]\\'><div class=\\'w-24 h-24 bg-dark-50 rounded-full flex items-center justify-center text-dark-200 text-4xl mb-4 border-2 border-dashed border-dark-200\\'><i class=\\'fas fa-qrcode\\'></i></div><h3 class=\\'font-bold text-xl text-dark-900 mb-2\\'>Menunggu Scan</h3><p class=\\'text-dark-500\\'>Hasil verifikasi tiket akan muncul di sini.</p></div>'" class="btn-secondary mt-8 w-full border-red-200 text-red-600 hover:bg-red-100">Scan Ulang</button>
                    </div>
                `;
                
                // Play error sound
                new Audio('https://www.soundjay.com/buttons/sounds/button-10.mp3').play().catch(e => {});
            }
        },
        
        renderError() {
            document.getElementById('result-container').innerHTML = `
                <div class="bg-red-50 rounded-2xl border border-red-200 shadow-sm p-8 flex flex-col items-center justify-center h-full text-center">
                    <i class="fas fa-exclamation-triangle text-5xl text-red-500 mb-4"></i>
                    <h3 class="font-bold text-xl text-red-800 mb-2">Error Server</h3>
                    <p class="text-red-600">Terjadi kesalahan saat memverifikasi QR Code.</p>
                </div>
            `;
        }
    }));
});
</script>
@endpush
@endsection

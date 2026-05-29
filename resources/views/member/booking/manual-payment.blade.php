@extends('layouts.app')
@section('title', 'Transfer Manual')
@section('content')
<div class="section-gradient min-h-screen pt-28 pb-16">
    <div class="container-custom max-w-3xl">

        <div class="mb-10 text-center reveal">
            <span class="text-sm font-bold uppercase tracking-widest mb-2 block" style="color: #00b3cc;">Pembayaran Manual</span>
            <h1 class="text-3xl lg:text-4xl font-display font-black text-dark-900 tracking-tight">Upload Bukti Transfer</h1>
            <p class="text-dark-500 mt-2 leading-relaxed">Silakan transfer sesuai nominal ke rekening di bawah ini.</p>
        </div>

        <div class="card-premium overflow-hidden reveal-scale shadow-2xl relative">
            <div class="absolute -top-20 -right-20 w-48 h-48 rounded-full blur-[60px]" style="background: rgba(0,229,255,0.15);"></div>
            <div class="p-8 md:p-10 relative z-10">

                {{-- Total Pembayaran Card --}}
                <div class="mb-8 p-6 rounded-2xl flex flex-col md:flex-row justify-between items-center gap-4 text-white relative overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border: 1px solid rgba(255,255,255,0.1);">
                    <div class="absolute inset-0 dot-pattern opacity-10"></div>
                    <div>
                        <span class="text-xs uppercase tracking-wider font-bold text-dark-400">Total Pembayaran</span>
                        <h3 class="text-3xl font-black mt-1 text-white">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-primary-500/10 border border-primary-500/30 px-4 py-2 rounded-xl text-center">
                        <span class="text-xs font-bold text-primary-400 block uppercase tracking-wider">Status Booking</span>
                        <span class="text-sm font-black text-white">Menunggu Pembayaran</span>
                    </div>
                </div>

                {{-- Bank List (BCA, Mandiri, BRI) --}}
                <div class="mb-8">
                    <label class="form-label block mb-3 font-bold text-dark-900">Pilih / Gunakan Rekening Transfer Berikut:</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- BCA Card -->
                        <div class="card-premium p-5 flex flex-col justify-between relative overflow-hidden group hover:scale-[1.02] transition-transform duration-300 border border-dark-100/50 bg-white shadow-md">
                            <div class="flex justify-between items-start mb-4">
                                <span class="bg-blue-600/10 text-blue-600 px-3 py-1 rounded-lg text-xs font-black tracking-wide uppercase">BCA</span>
                                <i class="fas fa-university text-dark-300 group-hover:text-blue-500 transition-colors"></i>
                            </div>
                            <div>
                                <span class="text-xs text-dark-400 font-medium block">No. Rekening</span>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="font-mono font-black text-dark-900 text-lg tracking-wide select-all" id="rek-bca">8015552222</span>
                                    <button type="button" onclick="copyText('rek-bca')" class="text-dark-400 hover:text-primary-500 transition-colors p-1" title="Salin">
                                        <i class="far fa-copy text-sm"></i>
                                    </button>
                                </div>
                                <span class="text-xs text-dark-500 font-bold mt-2 block">a.n {{ \App\Models\Pengaturan::getValue('nama_rekening', 'Lapsal Futsal') }}</span>
                            </div>
                        </div>

                        <!-- Mandiri Card -->
                        <div class="card-premium p-5 flex flex-col justify-between relative overflow-hidden group hover:scale-[1.02] transition-transform duration-300 border border-dark-100/50 bg-white shadow-md">
                            <div class="flex justify-between items-start mb-4">
                                <span class="bg-amber-600/10 text-amber-600 px-3 py-1 rounded-lg text-xs font-black tracking-wide uppercase">Mandiri</span>
                                <i class="fas fa-university text-dark-300 group-hover:text-amber-500 transition-colors"></i>
                            </div>
                            <div>
                                <span class="text-xs text-dark-400 font-medium block">No. Rekening</span>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="font-mono font-black text-dark-900 text-lg tracking-wide select-all" id="rek-mandiri">1370012345678</span>
                                    <button type="button" onclick="copyText('rek-mandiri')" class="text-dark-400 hover:text-primary-500 transition-colors p-1" title="Salin">
                                        <i class="far fa-copy text-sm"></i>
                                    </button>
                                </div>
                                <span class="text-xs text-dark-500 font-bold mt-2 block">a.n {{ \App\Models\Pengaturan::getValue('nama_rekening', 'Lapsal Futsal') }}</span>
                            </div>
                        </div>

                        <!-- BRI Card -->
                        <div class="card-premium p-5 flex flex-col justify-between relative overflow-hidden group hover:scale-[1.02] transition-transform duration-300 border border-dark-100/50 bg-white shadow-md">
                            <div class="flex justify-between items-start mb-4">
                                <span class="bg-blue-500/10 text-blue-500 px-3 py-1 rounded-lg text-xs font-black tracking-wide uppercase">BRI</span>
                                <i class="fas fa-university text-dark-300 group-hover:text-blue-500 transition-colors"></i>
                            </div>
                            <div>
                                <span class="text-xs text-dark-400 font-medium block">No. Rekening</span>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="font-mono font-black text-dark-900 text-sm tracking-wide select-all" id="rek-bri">002101001234501</span>
                                    <button type="button" onclick="copyText('rek-bri')" class="text-dark-400 hover:text-primary-500 transition-colors p-1" title="Salin">
                                        <i class="far fa-copy text-sm"></i>
                                    </button>
                                </div>
                                <span class="text-xs text-dark-500 font-bold mt-2 block">a.n {{ \App\Models\Pengaturan::getValue('nama_rekening', 'Lapsal Futsal') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Upload --}}
                <form action="{{ route('member.booking.payment.manual', $booking->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-6">
                        <label class="form-label block mb-2 font-bold text-dark-900">Upload Bukti Pembayaran <span class="text-red-500">*</span></label>
                        <p class="text-sm text-dark-500 mb-4">Pastikan nominal dan tanggal terlihat jelas pada foto bukti transfer.</p>

                        <div class="relative w-full">
                            <input type="file" name="bukti_transfer" id="bukti_transfer" class="hidden" accept="image/jpeg,image/png,image/jpg" required onchange="previewImage(event)">
                            <label for="bukti_transfer" class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed rounded-2xl cursor-pointer transition-all duration-300"
                                   style="background: rgba(0,0,0,0.02); border-color: rgba(0,0,0,0.1);"
                                   onmouseover="this.style.background='rgba(0,229,255,0.02)';this.style.borderColor='rgba(0,229,255,0.3)'"
                                   onmouseout="this.style.background='rgba(0,0,0,0.02)';this.style.borderColor='rgba(0,0,0,0.1)'">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6" id="upload-placeholder">
                                    <div class="w-12 h-12 rounded-xl mb-3 flex items-center justify-center" style="background: rgba(0,229,255,0.1); color: #007a8f;">
                                        <i class="fas fa-cloud-upload-alt text-2xl"></i>
                                    </div>
                                    <p class="mb-1 text-sm font-bold text-dark-900">Klik untuk upload gambar</p>
                                    <p class="text-xs text-dark-500">JPG, PNG atau JPEG (Max. 2MB)</p>
                                </div>
                                <div id="image-preview-container" class="hidden w-full h-full p-2 relative">
                                    <img id="image-preview" class="w-full h-full object-contain rounded-xl">
                                    <div class="absolute top-4 right-4 bg-white/90 p-2 rounded-lg shadow-sm text-xs font-bold text-dark-900 backdrop-blur-sm cursor-pointer border border-dark-100">
                                        Ganti Foto
                                    </div>
                                </div>
                            </label>
                        </div>
                        @error('bukti_transfer')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-4 pt-4 mt-8" style="border-top: 1px solid rgba(0,0,0,0.06);">
                        <a href="{{ route('member.booking.checkout', $booking->id) }}" class="btn-secondary w-1/3 text-center">Batal</a>
                        <button type="submit" class="btn-primary w-2/3">Konfirmasi Pembayaran <i class="fas fa-check-circle ml-2"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('upload-placeholder').classList.add('hidden');
                const container = document.getElementById('image-preview-container');
                container.classList.remove('hidden');
                container.classList.add('flex');
                document.getElementById('image-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function copyText(id) {
        const element = document.getElementById(id);
        const textToCopy = element.innerText;
        
        navigator.clipboard.writeText(textToCopy).then(() => {
            // Show toast or mini notification
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-5 right-5 bg-dark-900 text-white px-4 py-3 rounded-xl shadow-xl z-50 flex items-center gap-2 border border-dark-800 text-sm font-bold animate-fade-in-up';
            toast.innerHTML = '<i class="fas fa-check-circle text-primary-500"></i> Rekening berhasil disalin!';
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                setTimeout(() => toast.remove(), 300);
            }, 2000);
        }).catch(err => {
            console.error('Gagal menyalin: ', err);
        });
    }
</script>
@endpush
@endsection

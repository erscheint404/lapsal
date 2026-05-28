@extends('layouts.app')
@section('title', 'Detail Booking')
@section('content')
<div class="bg-dark-50 py-12 min-h-screen">
    <div class="container-custom max-w-4xl">
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('member.booking.index') }}" class="text-dark-500 hover:text-primary-600 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            <div class="flex gap-2">
                @if(in_array($booking->status, ['pending_payment', 'draft']))
                <a href="{{ route('member.booking.checkout', $booking->id) }}" class="btn-primary btn-sm">
                    Selesaikan Pembayaran
                </a>
                @endif

                @if($booking->status === 'confirmed' && $booking->tanggal >= today())
                <a href="{{ route('member.booking.reschedule', $booking->id) }}" class="btn-secondary btn-sm border-blue-200 text-blue-700 bg-blue-50 hover:bg-blue-100">
                    <i class="fas fa-calendar-alt mr-1"></i> Reschedule
                </a>
                <form action="{{ route('member.booking.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan booking ini?\n\nPerhatian: Uang yang dikembalikan hanya 75% dari total harga (Rp {{ number_format($booking->total_harga * 0.75, 0, ',', '.') }}).\n\nLanjutkan pembatalan?');" class="inline">
                    @csrf
                    <button type="submit" class="btn-secondary btn-sm border-red-200 text-red-700 bg-red-50 hover:bg-red-100">
                        <i class="fas fa-times mr-1"></i> Batal
                    </button>
                </form>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Main Column --}}
            <div class="md:col-span-2 space-y-6">
                {{-- Status Card --}}
                <div class="card p-6 border-l-4 {{ 
                    $booking->status === 'confirmed' ? 'border-emerald-500' : 
                    ($booking->status === 'pending_payment' ? 'border-amber-500' : 
                    ($booking->status === 'completed' ? 'border-blue-500' : 
                    ($booking->status === 'cancelled' || $booking->status === 'rejected' ? 'border-red-500' : 'border-dark-300'))) 
                }}">
                    <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
                        <div>
                            <p class="text-sm font-bold text-dark-500 mb-1">Status Booking</p>
                            <span class="badge {{ $booking->status_color }} text-sm px-3 py-1">{{ $booking->status_label }}</span>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-dark-500 mb-1">Kode Booking</p>
                            <p class="font-mono font-black text-dark-900 text-lg">{{ $booking->kode_booking }}</p>
                        </div>
                    </div>
                    
                    @if($booking->status === 'rejected' && $booking->alasan_penolakan)
                    <div class="bg-red-50 p-4 rounded-xl text-sm text-red-700 mt-4">
                        <strong>Alasan Penolakan:</strong> {{ $booking->alasan_penolakan }}
                    </div>
                    @endif
                </div>

                {{-- Detail Lapangan --}}
                <div class="card p-0 overflow-hidden">
                    <div class="p-6 border-b border-dark-100 flex gap-4">
                        <div class="w-24 h-24 rounded-xl bg-dark-100 overflow-hidden flex-none hidden sm:block">
                            @if($booking->lapangan->foto_utama)
                            <img src="{{ Storage::url($booking->lapangan->foto_utama) }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div>
                            <h3 class="font-bold text-xl text-dark-900 mb-1">{{ $booking->lapangan->nama }}</h3>
                            <span class="badge badge-success mb-3">{{ ucfirst(str_replace('_', ' ', $booking->lapangan->tipe)) }}</span>
                            <p class="text-sm text-dark-500 flex items-center gap-2">
                                <i class="fas fa-map-marker-alt"></i> {{ \App\Models\Pengaturan::getValue('alamat') }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="p-6 grid grid-cols-2 gap-y-6 gap-x-4">
                        <div>
                            <p class="text-xs text-dark-400 font-bold uppercase tracking-wider mb-1">Tanggal</p>
                            <p class="font-semibold text-dark-900">{{ $booking->tanggal->translatedFormat('l, d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-dark-400 font-bold uppercase tracking-wider mb-1">Waktu</p>
                            <p class="font-semibold text-dark-900">{{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }} WIB</p>
                        </div>
                        <div>
                            <p class="text-xs text-dark-400 font-bold uppercase tracking-wider mb-1">Durasi</p>
                            <p class="font-semibold text-dark-900">{{ $booking->durasi_jam }} Jam</p>
                        </div>
                        <div>
                            <p class="text-xs text-dark-400 font-bold uppercase tracking-wider mb-1">Metode Bayar</p>
                            <p class="font-semibold text-dark-900">{{ $booking->metode_pembayaran === 'midtrans' ? 'Otomatis' : ($booking->metode_pembayaran === 'manual' ? 'Transfer Manual' : '-') }}</p>
                        </div>
                    </div>
                    
                    <div class="p-6 bg-dark-50 border-t border-dark-100 flex flex-col gap-2">
                        @if($booking->reschedule_fee > 0)
                        <div class="flex justify-between items-center text-sm text-amber-600 font-bold">
                            <span>Biaya Reschedule (Bayar di Kasir)</span>
                            <span>+ Rp {{ number_format($booking->reschedule_fee, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        @if($booking->refund_amount > 0)
                        <div class="flex justify-between items-center text-sm text-red-600 font-bold">
                            <span>Refund Pembatalan (75%)</span>
                            <span>- Rp {{ number_format($booking->refund_amount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between items-center pt-2">
                            <span class="font-bold text-dark-900">Total Harga Sewa</span>
                            <span class="text-2xl font-black text-primary-600">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Rating Form (Only if completed and not rated) --}}
                @if($booking->canBeRated())
                <div class="card p-6" id="rating">
                    <h3 class="font-bold text-lg mb-4 flex items-center"><i class="fas fa-star text-amber-500 mr-2"></i> Beri Ulasan</h3>
                    <form action="{{ route('member.booking.rating', $booking->id) }}" method="POST">
                        @csrf
                        <div class="mb-4" x-data="{ rating: 0, hoverRating: 0 }">
                            <label class="block text-sm font-bold text-dark-700 mb-2">Rating</label>
                            <div class="flex gap-2">
                                <template x-for="i in 5">
                                    <button type="button" 
                                            @click="rating = i" 
                                            @mouseenter="hoverRating = i" 
                                            @mouseleave="hoverRating = 0"
                                            class="text-3xl focus:outline-none transition-colors"
                                            :class="i <= (hoverRating || rating) ? 'text-amber-400' : 'text-dark-200'">
                                        <i class="fas fa-star"></i>
                                    </button>
                                </template>
                            </div>
                            <input type="hidden" name="rating" x-model="rating" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-dark-700 mb-2">Ulasan (Opsional)</label>
                            <textarea name="ulasan" rows="3" class="form-input" placeholder="Ceritakan pengalaman bermain Anda di lapangan ini..."></textarea>
                        </div>
                        <button type="submit" class="btn-primary w-full">Kirim Ulasan</button>
                    </form>
                </div>
                @elseif($booking->rating)
                <div class="card p-6 border-l-4 border-amber-400">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-dark-900">Ulasan Anda</h3>
                        <div class="flex text-amber-400">
                            @for($i=0; $i<$booking->rating->rating; $i++) <i class="fas fa-star"></i> @endfor
                            @for($i=0; $i<5-$booking->rating->rating; $i++) <i class="far fa-star text-dark-200"></i> @endfor
                        </div>
                    </div>
                    @if($booking->rating->ulasan)
                    <p class="text-dark-600 italic">"{{ $booking->rating->ulasan }}"</p>
                    @endif
                </div>
                @endif
                
                {{-- Statistik Gol (If Completed and has stats) --}}
                @if($booking->status === 'completed' && $booking->statistikGol->count() > 0)
                <div class="card p-6">
                    <h3 class="font-bold text-lg mb-4 flex items-center"><i class="fas fa-futbol text-emerald-500 mr-2"></i> Statistik Gol Sesi Ini</h3>
                    <div class="space-y-3">
                        @foreach($booking->statistikGol as $stat)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-dark-50 border border-dark-100">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-dark-200 flex items-center justify-center"><i class="fas fa-user text-dark-400"></i></div>
                                <span class="font-bold text-dark-900">{{ $stat->nama_pemain }}</span>
                            </div>
                            <div class="font-black text-xl text-emerald-600">{{ $stat->jumlah_gol }} <span class="text-xs font-bold text-dark-400 uppercase">Gol</span></div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar Column --}}
            <div class="md:col-span-1 space-y-6">
                
                {{-- QR Code (Only if Confirmed) --}}
                @if($booking->status === 'confirmed' && $booking->qr_code_path)
                <div class="card p-6 text-center border-2 border-primary-100 bg-gradient-to-b from-white to-primary-50">
                    <h3 class="font-bold text-lg text-dark-900 mb-2">Tiket Masuk</h3>
                    <p class="text-xs text-dark-500 mb-6">Tunjukkan QR Code ini kepada petugas lapangan saat Anda tiba.</p>
                    
                    <div class="bg-white p-4 rounded-2xl inline-block shadow-lg mb-4">
                        <img src="{{ Storage::url($booking->qr_code_path) }}" alt="QR Code" class="w-48 h-48">
                    </div>
                    
                    <p class="font-mono font-bold text-dark-900 tracking-widest">{{ $booking->kode_booking }}</p>
                </div>
                @endif

                {{-- Riwayat --}}
                <div class="card p-6">
                    <h3 class="font-bold text-lg mb-6">Riwayat Status</h3>
                    
                    <div class="relative border-l-2 border-dark-100 ml-3 space-y-6">
                        @foreach($booking->riwayat()->latest()->get() as $riwayat)
                        <div class="relative pl-6">
                            <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full border-2 border-white {{ $loop->first ? 'bg-primary-500' : 'bg-dark-300' }}"></div>
                            <p class="text-xs text-dark-400 mb-1">{{ $riwayat->created_at->format('d M Y, H:i') }}</p>
                            <p class="font-bold text-sm text-dark-900 mb-1">{{ $riwayat->catatan }}</p>
                            @if($riwayat->status_baru)
                            <span class="text-xs font-medium px-2 py-0.5 rounded bg-dark-50 border border-dark-100">{{ $riwayat->status_baru }}</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

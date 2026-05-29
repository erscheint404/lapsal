@extends('layouts.app')
@section('title', 'Checkout Booking')
@section('content')
<div class="section-gradient min-h-screen pt-28 pb-16">
    <div class="container-custom max-w-5xl">

        <div class="mb-10 text-center max-w-2xl mx-auto reveal">
            <span class="text-sm font-bold uppercase tracking-widest mb-2 block" style="color: #00b3cc;">Tahap Akhir</span>
            <h1 class="text-3xl lg:text-4xl font-display font-black text-dark-900 tracking-tight">Selesaikan Pembayaran</h1>
            <p class="text-dark-500 mt-2 leading-relaxed">Amankan jadwal bermainmu sekarang sebelum diambil orang lain.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Form Column --}}
            <div class="lg:col-span-2 space-y-6 reveal-left">
                {{-- Warning Timer (Simulated) --}}
                <div class="rounded-2xl p-4 flex items-center justify-between" style="background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.2);">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-clock text-amber-500 text-lg"></i>
                        <span class="font-bold text-amber-700 text-sm">Selesaikan pembayaran sebelum:</span>
                    </div>
                    <span class="font-black text-amber-600 font-display text-lg tracking-wider">{{ $booking->created_at->addMinutes(30)->format('H:i') }}</span>
                </div>

                <div class="card-premium p-8">
                    <h3 class="font-bold text-xl text-dark-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-receipt" style="color: #6e8f00;"></i> Detail Pemesan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="form-label text-xs">Nama Lengkap</label>
                            <div class="font-bold text-dark-900">{{ auth()->user()->name }}</div>
                        </div>
                        <div>
                            <label class="form-label text-xs">Email</label>
                            <div class="font-bold text-dark-900">{{ auth()->user()->email }}</div>
                        </div>
                        <div>
                            <label class="form-label text-xs">No. Telepon</label>
                            <div class="font-bold text-dark-900">{{ auth()->user()->phone ?? '-' }}</div>
                        </div>
                        <div>
                            <label class="form-label text-xs">Kode Booking</label>
                            <div class="font-mono font-black text-lg" style="color: #6e8f00;">{{ $booking->kode_booking }}</div>
                        </div>
                    </div>
                </div>

                <div class="card-premium p-8">
                    <h3 class="font-bold text-xl text-dark-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-credit-card" style="color: #00b3cc;"></i> Metode Pembayaran
                    </h3>

                    <form action="{{ route('member.booking.pay', $booking->id) }}" method="POST" id="payment-form">
                        @csrf
                        <div class="space-y-4 mb-8">
                            {{-- Midtrans --}}
                            <label class="block cursor-pointer">
                                <input type="radio" name="payment_method" value="midtrans" class="peer sr-only" checked>
                                <div class="p-5 rounded-2xl border-2 transition-all duration-300 peer-checked:bg-white
                                     peer-checked:border-primary-500 peer-checked:shadow-lg peer-checked:shadow-primary-500/10
                                     bg-dark-50 border-dark-200/50 hover:border-dark-300 relative overflow-hidden group">
                                    <div class="absolute inset-0 opacity-0 peer-checked:opacity-100 transition-opacity"
                                         style="background: linear-gradient(135deg, rgba(204,255,0,0.05), transparent);"></div>
                                    <div class="flex items-center justify-between relative z-10">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 transition-all peer-checked:bg-primary-100 peer-checked:text-primary-700"
                                                 style="background: rgba(0,0,0,0.04); color: #627d9e;">
                                                <i class="fas fa-bolt text-xl"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-dark-900 text-lg">Bayar Otomatis (Midtrans)</h4>
                                                <p class="text-sm text-dark-500 mt-0.5">Transfer Bank, E-Wallet (OVO, GoPay), Kartu Kredit.</p>
                                            </div>
                                        </div>
                                        <div class="w-6 h-6 rounded-full border-2 border-dark-300 flex items-center justify-center peer-checked:border-primary-500 peer-checked:bg-primary-500">
                                            <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100"></i>
                                        </div>
                                    </div>
                                    <div class="mt-4 pt-4 hidden peer-checked:block transition-all" style="border-top: 1px dashed rgba(0,0,0,0.1);">
                                        <div class="flex gap-2">
                                            <span class="px-2 py-1 bg-dark-50 rounded text-xs font-bold text-dark-500 border border-dark-100">BCA</span>
                                            <span class="px-2 py-1 bg-dark-50 rounded text-xs font-bold text-dark-500 border border-dark-100">Mandiri</span>
                                            <span class="px-2 py-1 bg-dark-50 rounded text-xs font-bold text-dark-500 border border-dark-100">GoPay</span>
                                            <span class="px-2 py-1 bg-dark-50 rounded text-xs font-bold text-dark-500 border border-dark-100">OVO</span>
                                            <span class="text-xs text-dark-400 self-center ml-2">+ lainnya</span>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            {{-- Manual --}}
                            <label class="block cursor-pointer">
                                <input type="radio" name="payment_method" value="manual" class="peer sr-only">
                                <div class="p-5 rounded-2xl border-2 transition-all duration-300 peer-checked:bg-white
                                     peer-checked:border-accent-500 peer-checked:shadow-lg peer-checked:shadow-accent-500/10
                                     bg-dark-50 border-dark-200/50 hover:border-dark-300 relative overflow-hidden group">
                                     <div class="absolute inset-0 opacity-0 peer-checked:opacity-100 transition-opacity"
                                         style="background: linear-gradient(135deg, rgba(0,229,255,0.05), transparent);"></div>
                                    <div class="flex items-center justify-between relative z-10">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 transition-all peer-checked:bg-accent-100 peer-checked:text-accent-700"
                                                 style="background: rgba(0,0,0,0.04); color: #627d9e;">
                                                <i class="fas fa-file-invoice text-xl"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-dark-900 text-lg">Transfer Manual</h4>
                                                <p class="text-sm text-dark-500 mt-0.5">Transfer langsung ke rekening kami dan upload bukti.</p>
                                            </div>
                                        </div>
                                        <div class="w-6 h-6 rounded-full border-2 border-dark-300 flex items-center justify-center peer-checked:border-accent-500 peer-checked:bg-accent-500">
                                            <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100"></i>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <button type="submit" class="btn-primary w-full py-4 text-lg">
                            Proses Pembayaran <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Summary Sidebar --}}
            <div class="lg:col-span-1 reveal-right">
                <div class="card-premium p-6 sticky top-28 shadow-2xl relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full blur-[40px]" style="background: rgba(204,255,0,0.15);"></div>

                    <h3 class="font-bold text-xl text-dark-900 mb-6 relative z-10">Ringkasan Booking</h3>

                    <div class="flex gap-4 mb-6 relative z-10">
                        <div class="w-20 h-20 rounded-xl overflow-hidden shrink-0 border border-dark-100">
                            @if($booking->lapangan->foto_utama)
                            <img src="{{ Storage::url($booking->lapangan->foto_utama) }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full bg-dark-100 flex items-center justify-center text-dark-300"><i class="fas fa-image"></i></div>
                            @endif
                        </div>
                        <div>
                            <p class="font-bold text-dark-900 leading-tight mb-1">{{ $booking->lapangan->nama }}</p>
                            <span class="badge badge-info text-[10px]">{{ ucfirst(str_replace('_', ' ', $booking->lapangan->tipe)) }}</span>
                        </div>
                    </div>

                    <div class="space-y-4 mb-6 relative z-10 text-sm">
                        <div class="flex justify-between items-center pb-4" style="border-bottom: 1px dashed rgba(0,0,0,0.1);">
                            <span class="text-dark-500 flex items-center gap-2"><i class="far fa-calendar-alt text-dark-400"></i> Tanggal</span>
                            <span class="font-bold text-dark-900">{{ $booking->tanggal->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-4" style="border-bottom: 1px dashed rgba(0,0,0,0.1);">
                            <span class="text-dark-500 flex items-center gap-2"><i class="far fa-clock text-dark-400"></i> Waktu</span>
                            <span class="font-bold text-dark-900">{{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-4" style="border-bottom: 1px dashed rgba(0,0,0,0.1);">
                            <span class="text-dark-500 flex items-center gap-2"><i class="fas fa-hourglass-half text-dark-400"></i> Durasi</span>
                            <span class="font-bold text-dark-900">{{ $booking->durasi }} Jam</span>
                        </div>
                    </div>

                    <div class="pt-2 relative z-10">
                        <div class="p-4 rounded-2xl" style="background: rgba(10,18,33,0.95); border: 1px solid rgba(204,255,0,0.2);">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-medium text-dark-300">Total Pembayaran</span>
                            </div>
                            <div class="text-2xl font-display font-black" style="color: #ccff00; text-shadow: 0 0 20px rgba(204,255,0,0.2);">
                                Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
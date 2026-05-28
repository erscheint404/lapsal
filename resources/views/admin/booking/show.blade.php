@extends('layouts.admin')
@section('title', 'Detail Booking')
@section('page_title', 'Detail Booking')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        {{-- Detail Card --}}
        <div class="bg-white rounded-2xl border border-dark-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-dark-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('admin.booking.index') }}" class="btn-icon text-dark-500 bg-dark-50 hover:bg-dark-100"><i class="fas fa-arrow-left"></i></a>
                    <h2 class="font-bold text-lg text-dark-900">Informasi Booking <span class="font-mono ml-2 text-primary-600">{{ $booking->kode_booking }}</span></h2>
                </div>
                <span class="badge {{ $booking->status_color }} text-sm px-3 py-1">{{ $booking->status_label }}</span>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-2 gap-y-6 gap-x-4 mb-8">
                    <div>
                        <p class="text-xs font-bold text-dark-400 uppercase tracking-wider mb-1">Member</p>
                        <div class="flex items-center gap-3">
                            <img src="{{ $booking->user->avatar_url }}" class="w-8 h-8 rounded-full object-cover">
                            <div>
                                <p class="font-bold text-dark-900 text-sm">{{ $booking->user->name }}</p>
                                <p class="text-xs text-dark-500">{{ $booking->user->phone }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-dark-400 uppercase tracking-wider mb-1">Waktu Dibuat</p>
                        <p class="font-semibold text-dark-900 text-sm">{{ $booking->created_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>

                <div class="bg-dark-50 rounded-xl p-5 border border-dark-100 mb-6">
                    <div class="flex gap-4 items-center mb-4 border-b border-dark-200 pb-4">
                        <div class="w-16 h-16 rounded-xl bg-dark-200 overflow-hidden">
                            @if($booking->lapangan->foto_utama)
                            <img src="{{ Storage::url($booking->lapangan->foto_utama) }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-dark-900">{{ $booking->lapangan->nama }}</h3>
                            <span class="badge badge-success mt-1">{{ ucfirst(str_replace('_', ' ', $booking->lapangan->tipe)) }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 text-sm">
                        <div>
                            <p class="text-dark-500 mb-1">Tanggal</p>
                            <p class="font-bold text-dark-900">{{ $booking->tanggal->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-dark-500 mb-1">Jam Main</p>
                            <p class="font-bold text-dark-900">{{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }}</p>
                        </div>
                        <div>
                            <p class="text-dark-500 mb-1">Harga / Jam</p>
                            <p class="font-bold text-dark-900">Rp {{ number_format($booking->lapangan->harga_per_jam, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between bg-primary-50 border border-primary-100 rounded-xl p-5">
                    <div>
                        <p class="text-xs font-bold text-primary-600 uppercase tracking-wider mb-1">Total Pembayaran ({{ $booking->durasi_jam }} Jam)</p>
                        <p class="text-sm font-medium text-dark-600">Metode: <span class="font-bold text-dark-900">{{ $booking->metode_pembayaran === 'midtrans' ? 'Otomatis' : ($booking->metode_pembayaran === 'manual' ? 'Transfer Manual' : '-') }}</span></p>
                    </div>
                    <p class="text-3xl font-black text-primary-700">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                </div>

                {{-- Action Buttons --}}
                @if(in_array($booking->status, ['under_review', 'pending_payment', 'waiting_confirmation']))
                <div class="mt-8 flex flex-wrap gap-3 pt-6 border-t border-dark-100" x-data="{ showReject: false }">
                    <form action="{{ route('admin.booking.confirm', $booking->id) }}" method="POST" class="flex-1 md:flex-none">
                        @csrf
                        <button type="submit" class="btn-primary w-full shadow-lg shadow-emerald-500/20 bg-emerald-500 hover:bg-emerald-600 border-none" onclick="return confirm('Konfirmasi booking ini secara manual?')">
                            <i class="fas fa-check mr-2"></i> Konfirmasi Booking
                        </button>
                    </form>
                    <button type="button" @click="showReject = !showReject" class="btn-secondary flex-1 md:flex-none text-red-600 hover:bg-red-50 hover:border-red-200">
                        <i class="fas fa-times mr-2"></i> Tolak
                    </button>

                    {{-- Reject Form --}}
                    <div x-show="showReject" x-transition class="w-full mt-4 bg-red-50 border border-red-200 rounded-xl p-5">
                        <form action="{{ route('admin.booking.reject', $booking->id) }}" method="POST">
                            @csrf
                            <label class="form-label text-red-800">Alasan Penolakan</label>
                            <textarea name="alasan" rows="2" class="form-input mb-3" required placeholder="Contoh: Bukti transfer palsu / Lapangan maintenance mendadak"></textarea>
                            <div class="flex justify-end gap-2">
                                <button type="button" @click="showReject = false" class="btn-secondary py-1 px-3 text-sm">Batal</button>
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-1 px-4 rounded-lg text-sm transition-colors">Tolak Booking</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

                @if($booking->status === 'confirmed')
                <div class="mt-8 pt-6 border-t border-dark-100">
                    <form action="{{ route('admin.booking.complete', $booking->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-primary shadow-lg shadow-blue-500/20 bg-blue-500 hover:bg-blue-600 border-none w-full" onclick="return confirm('Selesaikan sesi booking ini?')">
                            <i class="fas fa-flag-checkered mr-2"></i> Tandai Sesi Selesai
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        
        {{-- Bukti Pembayaran (If Manual) --}}
        @if($booking->buktiPembayaran)
        <div class="bg-white rounded-2xl border border-dark-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-dark-100 flex justify-between items-center">
                <h3 class="font-bold text-lg text-dark-900">Bukti Transfer Manual</h3>
                <span class="badge {{ $booking->buktiPembayaran->status === 'verified' ? 'badge-success' : ($booking->buktiPembayaran->status === 'rejected' ? 'badge-error' : 'bg-amber-100 text-amber-800') }}">
                    {{ ucfirst($booking->buktiPembayaran->status) }}
                </span>
            </div>
            <div class="p-6">
                <a href="{{ Storage::url($booking->buktiPembayaran->file_path) }}" target="_blank" class="block bg-dark-50 rounded-xl overflow-hidden mb-4 border border-dark-200 hover:border-primary-500 transition-colors">
                    <img src="{{ Storage::url($booking->buktiPembayaran->file_path) }}" alt="Bukti Transfer" class="w-full max-h-96 object-contain">
                </a>
                
                @if($booking->status === 'under_review')
                <form action="{{ route('admin.pembayaran.verify', $booking->id) }}" method="POST" class="bg-dark-50 p-4 rounded-xl border border-dark-100">
                    @csrf
                    <label class="form-label text-sm">Catatan Verifikasi (Opsional)</label>
                    <input type="text" name="catatan" class="form-input text-sm py-2 mb-3" placeholder="Sesuai mutasi bank / Kurang bayar">
                    <div class="flex gap-3">
                        <button type="submit" name="action" value="approve" class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white font-medium py-2 rounded-lg text-sm transition-colors text-center" onclick="return confirm('Verifikasi pembayaran ini valid?')">Valid & Approve</button>
                        <button type="submit" name="action" value="reject" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-medium py-2 rounded-lg text-sm transition-colors text-center" onclick="return confirm('Tolak bukti pembayaran ini?')">Tidak Valid (Tolak)</button>
                    </div>
                </form>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- Sidebar Column --}}
    <div class="lg:col-span-1 space-y-6">
        {{-- Riwayat Status --}}
        <div class="bg-white rounded-2xl border border-dark-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-dark-100">
                <h3 class="font-bold text-lg text-dark-900">Riwayat Perubahan</h3>
            </div>
            <div class="p-6">
                <div class="relative border-l-2 border-dark-100 ml-3 space-y-6">
                    @foreach($booking->riwayat()->latest()->get() as $riwayat)
                    <div class="relative pl-6">
                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full border-2 border-white {{ $loop->first ? 'bg-primary-500' : 'bg-dark-300' }}"></div>
                        <p class="text-xs text-dark-400 mb-1">{{ $riwayat->created_at->format('d M Y, H:i') }}</p>
                        <p class="font-bold text-sm text-dark-900 mb-1">{{ $riwayat->catatan }}</p>
                        <div class="flex items-center gap-2">
                            @if($riwayat->status_baru)
                            <span class="text-[10px] font-medium px-2 py-0.5 rounded bg-dark-50 border border-dark-100 uppercase">{{ $riwayat->status_baru }}</span>
                            @endif
                            @if($riwayat->changedByUser)
                            <span class="text-[10px] text-dark-500"><i class="fas fa-user-shield mr-1"></i>{{ $riwayat->changedByUser->name }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Add Statistik Gol for completed sessions --}}
        @if($booking->status === 'completed')
        <div class="bg-white rounded-2xl border border-dark-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-dark-100 flex justify-between items-center">
                <h3 class="font-bold text-lg text-dark-900">Statistik Gol</h3>
            </div>
            
            @if($booking->statistikGol->count() > 0)
            <div class="p-4 border-b border-dark-100">
                <div class="space-y-2 mb-4">
                    @foreach($booking->statistikGol as $stat)
                    <div class="flex items-center justify-between p-2 rounded-lg bg-dark-50 border border-dark-100">
                        <span class="font-bold text-sm text-dark-900">{{ $stat->nama_pemain }}</span>
                        <div class="flex items-center gap-4">
                            <span class="font-black text-emerald-600">{{ $stat->jumlah_gol }} Gol</span>
                            <form action="{{ route('admin.statistik.destroy', $stat->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="p-6 bg-dark-50" x-data="{ players: [{nama: '', gol: 0}] }">
                <p class="text-xs font-bold text-dark-500 uppercase tracking-wider mb-3">Input Gol Baru</p>
                <form action="{{ route('admin.statistik.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                    
                    <template x-for="(player, index) in players" :key="index">
                        <div class="flex gap-2 mb-3">
                            <input type="text" :name="'pemain['+index+'][nama]'" x-model="player.nama" class="form-input text-sm py-1.5 flex-1" placeholder="Nama Pemain" required>
                            <input type="number" :name="'pemain['+index+'][gol]'" x-model="player.gol" class="form-input text-sm py-1.5 w-20 text-center" min="1" required>
                            <button type="button" @click="players.splice(index, 1)" x-show="players.length > 1" class="btn-secondary py-1 px-2 text-red-500"><i class="fas fa-times"></i></button>
                        </div>
                    </template>
                    
                    <div class="flex gap-2 mt-4">
                        <button type="button" @click="players.push({nama: '', gol: 0})" class="btn-secondary btn-sm flex-1 border-dashed border-2"><i class="fas fa-plus mr-1"></i> Baris</button>
                        <button type="submit" class="btn-primary btn-sm flex-1">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

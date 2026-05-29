@extends('layouts.admin')
@section('title', 'Leaderboard Management')
@section('page_title', 'Kelola Leaderboard')
@section('page_description', 'Kelola catatan skor gol pemain futsal (Maksimal 30 pemain).')

@section('page_actions')
<button type="button" @click="$dispatch('open-modal')" class="btn-primary">
    <i class="fas fa-plus mr-2"></i> Input Gol Pemain
</button>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Leaderboard Table --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="card-premium overflow-hidden">
            <div class="p-6 border-b border-dark-100/60 flex items-center justify-between" style="background: rgba(0,0,0,0.01);">
                <h3 class="font-bold text-dark-900 flex items-center gap-2">
                    <i class="fas fa-trophy text-amber-500"></i> Peringkat Pemain saat ini
                </h3>
                <span class="text-xs font-bold text-dark-500 bg-dark-100 px-3 py-1.5 rounded-full">
                    {{ $leaderboard->count() }} / 30 Pemain
                </span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="table-modern w-full">
                    <thead>
                        <tr>
                            <th class="py-4 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider w-16">Rank</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider">Nama Pemain</th>
                            <th class="py-4 px-6 text-center text-xs font-bold text-dark-400 uppercase tracking-wider">Total Sesi</th>
                            <th class="py-4 px-6 text-center text-xs font-bold text-dark-400 uppercase tracking-wider">Total Gol</th>
                            <th class="py-4 px-6 text-right text-xs font-bold text-dark-400 uppercase tracking-wider w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dark-100/40">
                        @forelse($leaderboard as $index => $player)
                        <tr class="hover:bg-dark-50/50 transition-colors group">
                            <td class="py-4 px-6">
                                @if($index == 0)
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-sm" style="background: rgba(204,255,0,0.15); color: #6e8f00; border: 1px solid rgba(204,255,0,0.3);">
                                        <i class="fas fa-crown text-xs"></i>
                                    </div>
                                @elseif($index == 1)
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-sm bg-slate-100 text-slate-700 border border-slate-200">
                                        2
                                    </div>
                                @elseif($index == 2)
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-sm bg-orange-50 text-orange-700 border border-orange-200">
                                        3
                                    </div>
                                @else
                                    <span class="text-sm font-bold text-dark-400 pl-3">{{ $index + 1 }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-dark-900 flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-dark-50 text-dark-400 flex items-center justify-center text-xs border border-dark-100">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    {{ $player->nama_pemain }}
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="font-semibold text-dark-700">{{ $player->total_sesi }} Sesi</span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-xl font-bold text-sm bg-primary-500/10 text-primary-600 border border-primary-500/20">
                                    {{ $player->total_gol }} Gol
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <form action="{{ route('admin.leaderboard.destroy', $player->nama_pemain) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pemain ini dari leaderboard? Semua statistik gol pemain ini akan terhapus.');" class="inline-block opacity-0 group-hover:opacity-100 transition-opacity">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center bg-dark-50 text-dark-500 hover:bg-red-50 hover:text-red-600 transition-colors tooltip" data-tip="Hapus dari Leaderboard">
                                        <i class="fas fa-trash-alt text-sm"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background: rgba(0,0,0,0.03); color: #627d9e;">
                                    <i class="fas fa-trophy text-2xl"></i>
                                </div>
                                <p class="text-dark-900 font-bold mb-1">Belum ada data leaderboard</p>
                                <p class="text-sm text-dark-500">Mulai input gol pemain untuk memunculkan leaderboard.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Rules & Guide Column --}}
    <div class="space-y-6">
        <div class="card-premium p-6" style="background: linear-gradient(135deg, #1a2740 0%, #0f1b2e 100%); border-color: rgba(255,255,255,0.06); color: white;">
            <h3 class="font-bold text-lg mb-4 text-white flex items-center gap-2">
                <i class="fas fa-info-circle" style="color: #ccff00;"></i> Aturan Leaderboard
            </h3>
            <ul class="space-y-3.5 text-sm text-dark-300">
                <li class="flex items-start gap-2.5">
                    <i class="fas fa-check-circle mt-0.5 shrink-0" style="color: #ccff00;"></i>
                    <span><strong>Batas 30 Pemain</strong>: Sistem membatasi hanya top 30 pencetak gol terbanyak yang masuk ke papan peringkat utama.</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <i class="fas fa-check-circle mt-0.5 shrink-0" style="color: #ccff00;"></i>
                    <span><strong>Verifikasi Booking</strong>: Catatan gol harus terikat ke sebuah booking lapangan yang statusnya telah <strong>Selesai (Completed)</strong>.</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <i class="fas fa-check-circle mt-0.5 shrink-0" style="color: #ccff00;"></i>
                    <span><strong>Penghapusan Pemain</strong>: Menghapus pemain dari panel ini akan menghapus semua statistik gol yang terikat pada nama pemain tersebut.</span>
                </li>
            </ul>
        </div>
    </div>
</div>

{{-- Modal Input Gol --}}
<div x-data="{ open: false }" 
     @open-modal.window="open = true" 
     @keydown.escape.window="open = false"
     x-show="open" 
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;"
     aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        {{-- Backdrop --}}
        <div x-show="open" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
             class="fixed inset-0 transition-opacity" aria-hidden="true"
             style="background: rgba(10,18,33,0.6); backdrop-filter: blur(4px);"
             @click="open = false"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        {{-- Modal Panel --}}
        <div x-show="open" 
             x-transition:enter="ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200 transform" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             class="relative z-10 inline-block w-full max-w-md px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:p-6"
             style="border: 1px solid rgba(0,0,0,0.08);">
            
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-dark-900" id="modal-title">Input Gol Pemain</h3>
                <button @click="open = false" class="w-8 h-8 flex items-center justify-center rounded-lg text-dark-400 hover:text-dark-900 hover:bg-dark-50 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.leaderboard.store') }}" method="POST">
                @csrf
                <div class="space-y-5 mb-8">
                    <div>
                        <label class="form-label block mb-2">Pilih Sesi / Booking Selesai</label>
                        <select name="booking_id" class="form-input text-sm" required>
                            <option value="">-- Pilih Sesi Pertandingan --</option>
                            @foreach($bookings as $booking)
                            <option value="{{ $booking->id }}">
                                {{ $booking->kode_booking }} - {{ $booking->lapangan->nama }} ({{ $booking->tanggal->format('d M Y') }})
                            </option>
                            @endforeach
                        </select>
                        @if($bookings->isEmpty())
                        <p class="text-xs text-red-500 mt-1"><i class="fas fa-exclamation-triangle"></i> Belum ada booking dengan status "Selesai" untuk dikaitkan.</p>
                        @endif
                    </div>

                    <div>
                        <label class="form-label block mb-2">Nama Pemain</label>
                        <input type="text" name="nama_pemain" class="form-input" placeholder="Contoh: Arya Teja" required>
                    </div>

                    <div>
                        <label class="form-label block mb-2">Jumlah Gol</label>
                        <input type="number" name="jumlah_gol" class="form-input" min="1" placeholder="Masukkan jumlah gol" required>
                    </div>
                </div>
                
                <div class="flex gap-3 pt-4" style="border-top: 1px solid rgba(0,0,0,0.06);">
                    <button type="button" @click="open = false" class="btn-secondary w-full text-center">Batal</button>
                    <button type="submit" class="btn-primary w-full" @if($bookings->isEmpty()) disabled style="opacity: 0.5; cursor: not-allowed;" @endif>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

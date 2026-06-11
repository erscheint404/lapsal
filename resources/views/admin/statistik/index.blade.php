@extends('layouts.admin')
@section('title', 'Statistik Gol')
@section('page_title', 'Statistik Gol')
@section('page_description', 'Pencatatan detail gol per pemain per sesi pertandingan.')

@section('page_actions')
<button type="button" @click="$dispatch('open-modal')" class="btn-primary">
    <i class="fas fa-plus mr-2"></i> Catat Gol
</button>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Search --}}
    <div class="card-premium p-4">
        <form method="GET" class="flex items-center gap-3">
            <div class="relative flex-1">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-400"><i class="fas fa-search text-sm"></i></span>
                <input type="text" name="search" class="form-input pl-11 text-sm" placeholder="Cari nama pemain..." value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn-primary text-sm px-5 py-3">Cari</button>
            @if(request('search'))
            <a href="{{ route('admin.statistik.index') }}" class="btn-secondary text-sm px-4 py-3">Reset</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="card-premium overflow-hidden">
        <div class="p-6 border-b border-dark-100/60" style="background: rgba(0,0,0,0.01);">
            <h3 class="font-bold text-dark-900 flex items-center gap-2">
                <i class="fas fa-futbol" style="color: #10b981;"></i> Riwayat Pencatatan Gol
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="table-modern w-full">
                <thead>
                    <tr>
                        <th class="py-4 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider w-16">No</th>
                        <th class="py-4 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider">Nama Pemain</th>
                        <th class="py-4 px-6 text-center text-xs font-bold text-dark-400 uppercase tracking-wider">Jumlah Gol</th>
                        <th class="py-4 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider">Booking</th>
                        <th class="py-4 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider">Lapangan</th>
                        <th class="py-4 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider">Tanggal Input</th>
                        <th class="py-4 px-6 text-right text-xs font-bold text-dark-400 uppercase tracking-wider w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-100/40">
                    @forelse($statistik as $index => $stat)
                    <tr class="hover:bg-dark-50/50 transition-colors group">
                        <td class="py-4 px-6 text-sm text-dark-500 font-medium">{{ $statistik->firstItem() + $index }}</td>
                        <td class="py-4 px-6">
                            <span class="font-bold text-dark-900">{{ $stat->nama_pemain }}</span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-xl font-bold text-sm bg-primary-500/10 text-primary-600 border border-primary-500/20">
                                {{ $stat->jumlah_gol }} Gol
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            @if($stat->booking)
                            <span class="text-sm font-mono text-dark-600">{{ $stat->booking->kode_booking }}</span>
                            @else
                            <span class="text-sm text-dark-400">-</span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            @if($stat->booking && $stat->booking->lapangan)
                            <span class="text-sm text-dark-700">{{ $stat->booking->lapangan->nama }}</span>
                            @else
                            <span class="text-sm text-dark-400">-</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-sm text-dark-500">{{ $stat->created_at->format('d M Y H:i') }}</td>
                        <td class="py-4 px-6 text-right">
                            <form action="{{ route('admin.statistik.destroy', $stat->id) }}" method="POST" onsubmit="return confirm('Hapus data statistik ini?');" class="inline-block opacity-0 group-hover:opacity-100 transition-opacity">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center bg-dark-50 text-dark-500 hover:bg-red-50 hover:text-red-600 transition-colors">
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center">
                            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background: rgba(0,0,0,0.03); color: #627d9e;">
                                <i class="fas fa-futbol text-2xl"></i>
                            </div>
                            <p class="text-dark-900 font-bold mb-1">Belum ada data statistik gol</p>
                            <p class="text-sm text-dark-500">Mulai catat gol pemain dari sesi yang sudah selesai.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($statistik->hasPages())
        <div class="p-4 border-t border-dark-100/60">
            {{ $statistik->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Modal Input Gol --}}
<div x-data="statistikForm()" 
     @open-modal.window="open = true" 
     @keydown.escape.window="open = false"
     x-show="open" 
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;"
     aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="open" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
             class="fixed inset-0 transition-opacity" aria-hidden="true"
             style="background: rgba(10,18,33,0.6); backdrop-filter: blur(4px);"
             @click="open = false"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="open" 
             x-transition:enter="ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200 transform" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             class="relative z-10 inline-block w-full max-w-lg px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:p-6"
             style="border: 1px solid rgba(0,0,0,0.08);">
            
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-dark-900" id="modal-title">Catat Statistik Gol</h3>
                <button @click="open = false" class="w-8 h-8 flex items-center justify-center rounded-lg text-dark-400 hover:text-dark-900 hover:bg-dark-50 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.statistik.store') }}" method="POST">
                @csrf
                <div class="space-y-5 mb-6">
                    <div>
                        <label class="form-label block mb-2">Pilih Sesi / Booking Selesai</label>
                        <select name="booking_id" class="form-input text-sm" required>
                            <option value="">-- Pilih Sesi Pertandingan --</option>
                            @foreach($bookings as $booking)
                            <option value="{{ $booking->id }}">
                                {{ $booking->kode_booking }} - {{ $booking->lapangan->nama }} ({{ $booking->tanggal->format('d M Y') }}) — {{ $booking->user->name }}
                            </option>
                            @endforeach
                        </select>
                        @if($bookings->isEmpty())
                        <p class="text-xs text-red-500 mt-1"><i class="fas fa-exclamation-triangle"></i> Belum ada booking dengan status "Selesai".</p>
                        @endif
                    </div>

                    <div>
                        <label class="form-label block mb-3">Data Pemain & Gol</label>
                        <template x-for="(pemain, index) in pemainList" :key="index">
                            <div class="flex items-center gap-3 mb-3">
                                <input type="text" :name="'pemain['+index+'][nama]'" x-model="pemain.nama" class="form-input text-sm flex-1" placeholder="Nama Pemain" required>
                                <input type="number" :name="'pemain['+index+'][gol]'" x-model="pemain.gol" class="form-input text-sm w-24 text-center" placeholder="Gol" min="0" required>
                                <button type="button" @click="removePemain(index)" x-show="pemainList.length > 1" class="w-9 h-9 rounded-lg flex items-center justify-center bg-dark-50 text-dark-500 hover:bg-red-50 hover:text-red-600 transition-colors shrink-0">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </div>
                        </template>
                        <button type="button" @click="addPemain()" class="text-sm text-primary-600 hover:text-primary-700 font-bold mt-1">
                            <i class="fas fa-plus mr-1"></i> Tambah Pemain
                        </button>
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

@push('scripts')
<script>
function statistikForm() {
    return {
        open: false,
        pemainList: [{ nama: '', gol: '' }],
        addPemain() {
            this.pemainList.push({ nama: '', gol: '' });
        },
        removePemain(index) {
            this.pemainList.splice(index, 1);
        }
    }
}
</script>
@endpush
@endsection

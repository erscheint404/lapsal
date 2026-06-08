@extends('layouts.admin')
@section('title', 'Slot Waktu')
@section('page_title', 'Slot Waktu')
@section('page_description', 'Kelola ketersediaan jam operasional lapangan futsal.')

@section('page_actions')
<div class="flex items-center gap-3">
    <form method="GET" action="{{ route('admin.slot.index') }}" class="flex items-center gap-2">
        <div class="relative">
            <i class="fas fa-calendar-alt absolute left-3 top-1/2 -translate-y-1/2 text-dark-400 text-sm pointer-events-none"></i>
            <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()"
                   class="form-input pl-9 pr-4 py-2.5 text-sm rounded-xl" style="min-width: 180px;">
        </div>
    </form>
    <button type="button" @click="$dispatch('open-modal')" class="btn-primary">
        <i class="fas fa-plus mr-2"></i> Tambah Slot
    </button>
</div>
@endsection

@section('content')

{{-- Info Banner --}}
<div class="card-premium overflow-hidden relative mb-6">
    <div class="p-5" style="background: rgba(0,0,0,0.01);">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background: rgba(16,185,129,0.12);">
                <i class="fas fa-calendar-day text-lg" style="color: #10b981;"></i>
            </div>
            <div>
                <p class="font-bold text-dark-900">
                    {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
                </p>
                <p class="text-sm text-dark-500">Menampilkan slot waktu untuk semua lapangan aktif pada tanggal ini.</p>
            </div>
        </div>
    </div>
</div>

{{-- Lapangan Cards --}}
@forelse($lapanganList as $lapangan)
<div class="card-premium overflow-hidden relative mb-6" x-data="{ expanded: true }">
    {{-- Lapangan Header --}}
    <div class="p-5 flex items-center justify-between cursor-pointer select-none hover:bg-dark-50/30 transition-colors"
         @click="expanded = !expanded">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl overflow-hidden shrink-0 shadow-sm" style="border: 2px solid rgba(0,0,0,0.06);">
                @if($lapangan->foto_utama)
                    <img src="{{ asset('storage/' . $lapangan->foto_utama) }}" alt="{{ $lapangan->nama }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, #e8f5e9, #c8e6c9);">
                        <i class="fas fa-futbol text-lg" style="color: #2e7d32;"></i>
                    </div>
                @endif
            </div>
            <div>
                <h3 class="font-bold text-dark-900 text-lg">{{ $lapangan->nama }}</h3>
                <div class="flex items-center gap-3 mt-0.5">
                    <span class="text-sm text-dark-500">
                        <i class="fas fa-tag mr-1 text-xs"></i>{{ ucfirst($lapangan->tipe ?? 'Standar') }}
                    </span>
                    <span class="text-dark-200">•</span>
                    <span class="text-sm font-semibold" style="color: #10b981;">
                        {{ $lapangan->formatted_harga }}/jam
                    </span>
                    <span class="text-dark-200">•</span>
                    <span class="text-sm text-dark-500">
                        <i class="fas fa-clock mr-1 text-xs"></i>{{ $lapangan->slotWaktu->count() }} slot
                    </span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="badge" style="background: rgba(16,185,129,0.15); color: #059669; border: 1px solid rgba(16,185,129,0.3);">
                {{ ucfirst($lapangan->status) }}
            </span>
            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-dark-400 transition-transform duration-300"
                 :class="expanded ? 'rotate-180' : ''">
                <i class="fas fa-chevron-down text-sm"></i>
            </div>
        </div>
    </div>

    {{-- Slot Table --}}
    <div x-show="expanded" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2">
        <div class="overflow-x-auto" style="border-top: 1px solid rgba(0,0,0,0.06);">
            @if($lapangan->slotWaktu->count() > 0)
            <table class="table-modern w-full">
                <thead>
                    <tr>
                        <th class="py-3.5 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider w-16">No</th>
                        <th class="py-3.5 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider">Jam Mulai</th>
                        <th class="py-3.5 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider">Jam Selesai</th>
                        <th class="py-3.5 px-6 text-center text-xs font-bold text-dark-400 uppercase tracking-wider">Status</th>
                        <th class="py-3.5 px-6 text-right text-xs font-bold text-dark-400 uppercase tracking-wider w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-100/40">
                    @foreach($lapangan->slotWaktu as $key => $slot)
                    <tr class="hover:bg-dark-50/50 transition-colors group">
                        <td class="py-3.5 px-6 text-sm font-medium text-dark-500">{{ $key + 1 }}</td>
                        <td class="py-3.5 px-6">
                            <div class="font-bold text-dark-900 flex items-center gap-2">
                                <i class="far fa-clock text-dark-400"></i> {{ substr($slot->jam_mulai, 0, 5) }} WIB
                            </div>
                        </td>
                        <td class="py-3.5 px-6">
                            <div class="font-bold text-dark-900 flex items-center gap-2">
                                <i class="far fa-clock text-dark-400"></i> {{ substr($slot->jam_selesai, 0, 5) }} WIB
                            </div>
                        </td>
                        <td class="py-3.5 px-6 text-center">
                            @if($slot->status === 'available')
                                <span class="badge" style="background: rgba(16,185,129,0.15); color: #059669; border: 1px solid rgba(16,185,129,0.3);">Tersedia</span>
                            @elseif($slot->status === 'booked')
                                <span class="badge" style="background: rgba(59,130,246,0.1); color: #1d4ed8; border: 1px solid rgba(59,130,246,0.2);">Dipesan</span>
                            @elseif($slot->status === 'blocked')
                                <span class="badge" style="background: rgba(239,68,68,0.1); color: #dc2626; border: 1px solid rgba(239,68,68,0.2);">Diblokir</span>
                            @else
                                <span class="badge" style="background: rgba(0,0,0,0.05); color: #64748b; border: 1px solid rgba(0,0,0,0.1);">{{ ucfirst($slot->status) }}</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-6 text-right">
                            <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                @if($slot->status === 'available')
                                <form action="{{ route('admin.slot.status', $slot->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="blocked">
                                    <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center bg-dark-50 text-dark-500 hover:bg-amber-50 hover:text-amber-600 transition-colors tooltip" data-tip="Blokir">
                                        <i class="fas fa-ban text-sm"></i>
                                    </button>
                                </form>
                                @elseif($slot->status === 'blocked')
                                <form action="{{ route('admin.slot.status', $slot->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="available">
                                    <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center bg-dark-50 text-dark-500 hover:bg-green-50 hover:text-green-600 transition-colors tooltip" data-tip="Buka">
                                        <i class="fas fa-lock-open text-sm"></i>
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('admin.slot.destroy', $slot->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus slot waktu ini?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center bg-dark-50 text-dark-500 hover:bg-red-50 hover:text-red-600 transition-colors tooltip" data-tip="Hapus">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="py-10 text-center">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-3" style="background: rgba(0,0,0,0.03); color: #627d9e;">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <p class="text-dark-900 font-bold mb-1">Belum ada slot waktu</p>
                <p class="text-sm text-dark-500">Belum ada slot untuk lapangan ini pada tanggal {{ \Carbon\Carbon::parse($tanggal)->format('d/m/Y') }}.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@empty
<div class="card-premium overflow-hidden relative">
    <div class="py-16 text-center">
        <div class="w-20 h-20 rounded-3xl flex items-center justify-center mx-auto mb-4" style="background: rgba(0,0,0,0.03); color: #627d9e;">
            <i class="fas fa-futbol text-3xl"></i>
        </div>
        <p class="text-dark-900 font-bold text-lg mb-1">Belum ada lapangan aktif</p>
        <p class="text-sm text-dark-500 mb-6">Tambahkan lapangan terlebih dahulu sebelum mengatur slot waktu.</p>
        <a href="{{ route('admin.lapangan.index') }}" class="btn-primary inline-flex">
            <i class="fas fa-plus mr-2"></i> Tambah Lapangan
        </a>
    </div>
</div>
@endforelse

{{-- Modal Tambah --}}
<div x-data="{ open: false, lapangan_id: '{{ $lapanganId }}' }" 
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
                <h3 class="text-xl font-bold text-dark-900" id="modal-title">Tambah Slot Waktu</h3>
                <button @click="open = false" class="w-8 h-8 flex items-center justify-center rounded-lg text-dark-400 hover:text-dark-900 hover:bg-dark-50 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.slot.store') }}" method="POST">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                <div class="space-y-5 mb-8">
                    <div>
                        <label class="form-label block mb-2">Lapangan</label>
                        <select name="lapangan_id" x-model="lapangan_id" class="form-input" required>
                            @foreach($lapanganList as $lap)
                                <option value="{{ $lap->id }}">{{ $lap->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label block mb-2">Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label block mb-2">Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="form-input" required>
                    </div>
                </div>
                
                <div class="flex gap-3 pt-4" style="border-top: 1px solid rgba(0,0,0,0.06);">
                    <button type="button" @click="open = false" class="btn-secondary w-full text-center">Batal</button>
                    <button type="submit" class="btn-primary w-full">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
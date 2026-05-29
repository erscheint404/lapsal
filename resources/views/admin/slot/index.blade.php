@extends('layouts.admin')
@section('title', 'Slot Waktu')
@section('page_title', 'Slot Waktu')
@section('page_description', 'Kelola ketersediaan jam operasional lapangan futsal.')

@section('page_actions')
<button type="button" @click="$dispatch('open-modal')" class="btn-primary">
    <i class="fas fa-plus mr-2"></i> Tambah Slot
</button>
@endsection

@section('content')
<div class="card-premium overflow-hidden relative">
    <div class="p-6 border-b border-dark-100/60" style="background: rgba(0,0,0,0.01);">
        <p class="text-sm text-dark-500"><i class="fas fa-info-circle mr-1.5 text-primary-500"></i>Slot waktu ini akan berlaku untuk semua lapangan saat member melakukan booking.</p>
    </div>
    
    <div class="overflow-x-auto">
        <table class="table-modern w-full">
            <thead>
                <tr>
                    <th class="py-4 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider w-16">No</th>
                    <th class="py-4 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider">Jam Mulai</th>
                    <th class="py-4 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider">Jam Selesai</th>
                    <th class="py-4 px-6 text-center text-xs font-bold text-dark-400 uppercase tracking-wider">Status</th>
                    <th class="py-4 px-6 text-right text-xs font-bold text-dark-400 uppercase tracking-wider w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-100/40">
                @forelse($slots as $key => $slot)
                <tr class="hover:bg-dark-50/50 transition-colors group">
                    <td class="py-4 px-6 text-sm font-medium text-dark-500">{{ $key + 1 }}</td>
                    <td class="py-4 px-6">
                        <div class="font-bold text-dark-900 flex items-center gap-2">
                            <i class="far fa-clock text-dark-400"></i> {{ substr($slot->jam_mulai, 0, 5) }} WIB
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="font-bold text-dark-900 flex items-center gap-2">
                            <i class="far fa-clock text-dark-400"></i> {{ substr($slot->jam_selesai, 0, 5) }} WIB
                        </div>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <span class="badge" style="background: rgba(204,255,0,0.15); color: #526b00; border: 1px solid rgba(204,255,0,0.3);">Aktif</span>
                    </td>
                    <td class="py-4 px-6 text-right">
                        <form action="{{ route('admin.slot.destroy', $slot->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus slot waktu ini?');" class="inline-block opacity-0 group-hover:opacity-100 transition-opacity">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center bg-dark-50 text-dark-500 hover:bg-red-50 hover:text-red-600 transition-colors tooltip" data-tip="Hapus">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-12 text-center">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background: rgba(0,0,0,0.03); color: #627d9e;">
                            <i class="fas fa-clock text-2xl"></i>
                        </div>
                        <p class="text-dark-900 font-bold mb-1">Belum ada slot waktu</p>
                        <p class="text-sm text-dark-500">Mulai tambahkan jam operasional lapangan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah --}}
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
                <h3 class="text-xl font-bold text-dark-900" id="modal-title">Tambah Slot Waktu</h3>
                <button @click="open = false" class="w-8 h-8 flex items-center justify-center rounded-lg text-dark-400 hover:text-dark-900 hover:bg-dark-50 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.slot.store') }}" method="POST">
                @csrf
                <input type="hidden" name="lapangan_id" value="{{ $lapanganId }}">
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                <div class="space-y-5 mb-8">
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
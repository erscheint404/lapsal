@extends('layouts.admin')
@section('title', 'Manajemen Slot Waktu')
@section('page_title', 'Manajemen Slot Waktu')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
    
    {{-- Filter & Generator --}}
    <div class="xl:col-span-1 space-y-6">
        
        {{-- Filter Box --}}
        <div class="bg-white rounded-2xl border border-dark-100 shadow-sm p-6">
            <h3 class="font-bold text-lg text-dark-900 mb-4">Pilih Jadwal</h3>
            <form action="{{ route('admin.slot.index') }}" method="GET" class="space-y-4">
                <div>
                    <label class="form-label">Pilih Lapangan</label>
                    <select name="lapangan_id" class="form-input" onchange="this.form.submit()">
                        <option value="">-- Pilih Lapangan --</option>
                        @foreach($lapanganList as $l)
                        <option value="{{ $l->id }}" {{ $lapanganId == $l->id ? 'selected' : '' }}>{{ $l->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-input" onchange="this.form.submit()">
                </div>
            </form>
        </div>

        {{-- Generator Box --}}
        @if($lapanganId)
        <div class="bg-primary-50 rounded-2xl border border-primary-200 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-4 text-primary-700">
                <i class="fas fa-magic text-xl"></i>
                <h3 class="font-bold text-lg">Generate Slot Otomatis</h3>
            </div>
            <p class="text-sm text-primary-600/80 mb-4">Buat banyak slot waktu sekaligus untuk beberapa hari kedepan.</p>
            
            <form action="{{ route('admin.slot.generate') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="lapangan_id" value="{{ $lapanganId }}">
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="text-xs font-bold text-primary-700 uppercase">Periode Tanggal</label>
                    </div>
                    <div>
                        <input type="date" name="tanggal_mulai" value="{{ today()->format('Y-m-d') }}" min="{{ today()->format('Y-m-d') }}" class="form-input py-2 text-sm" required>
                    </div>
                    <div>
                        <input type="date" name="tanggal_selesai" value="{{ today()->addDays(7)->format('Y-m-d') }}" min="{{ today()->format('Y-m-d') }}" class="form-input py-2 text-sm" required>
                    </div>
                    
                    <div class="col-span-2">
                        <label class="text-xs font-bold text-primary-700 uppercase">Jam Operasional</label>
                    </div>
                    <div>
                        <div class="relative">
                            <input type="number" name="jam_buka" value="{{ \App\Models\Pengaturan::getValue('jam_buka', '08') }}" class="form-input py-2 text-sm pl-8" min="0" max="23" required>
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-primary-500 text-xs">Mulai</span>
                        </div>
                    </div>
                    <div>
                        <div class="relative">
                            <input type="number" name="jam_tutup" value="{{ \App\Models\Pengaturan::getValue('jam_tutup', '23') }}" class="form-input py-2 text-sm pl-8" min="1" max="24" required>
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-primary-500 text-xs">Selesai</span>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn-primary w-full shadow-lg shadow-primary-500/20" onclick="return confirm('Generate slot mungkin membutuhkan waktu beberapa detik. Lanjutkan?')">
                    Generate Slot
                </button>
            </form>
        </div>
        @endif

    </div>

    {{-- Slot Grid --}}
    <div class="xl:col-span-3">
        <div class="bg-white rounded-2xl border border-dark-100 shadow-sm p-6 min-h-[600px]">
            @if(!$lapanganId)
            <div class="h-full flex flex-col items-center justify-center text-dark-400 py-32">
                <i class="fas fa-hand-pointer text-5xl mb-4 text-dark-200"></i>
                <p class="font-bold text-lg text-dark-800">Pilih Lapangan</p>
                <p>Silakan pilih lapangan dari panel sebelah kiri terlebih dahulu.</p>
            </div>
            @elseif($slots->isEmpty())
            <div class="h-full flex flex-col items-center justify-center text-dark-400 py-32">
                <i class="far fa-calendar-times text-5xl mb-4 text-dark-200"></i>
                <p class="font-bold text-lg text-dark-800">Slot Kosong</p>
                <p>Belum ada slot waktu yang digenerate untuk tanggal {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}.</p>
                <p class="mt-2 text-sm">Gunakan fitur "Generate Slot Otomatis" di sebelah kiri.</p>
            </div>
            @else
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-dark-100">
                <div>
                    <h3 class="font-bold text-xl text-dark-900">{{ \App\Models\Lapangan::find($lapanganId)->nama }}</h3>
                    <p class="text-dark-500">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="flex gap-2">
                    <div class="flex items-center gap-1 text-xs font-bold text-dark-500"><span class="w-3 h-3 rounded bg-white border-2 border-dark-200"></span> Tersedia</div>
                    <div class="flex items-center gap-1 text-xs font-bold text-dark-500"><span class="w-3 h-3 rounded bg-amber-500"></span> Di-booking</div>
                    <div class="flex items-center gap-1 text-xs font-bold text-dark-500"><span class="w-3 h-3 rounded bg-emerald-500"></span> Selesai</div>
                    <div class="flex items-center gap-1 text-xs font-bold text-dark-500"><span class="w-3 h-3 rounded bg-red-500"></span> Diblokir</div>
                </div>
            </div>

            <form action="{{ route('admin.slot.bulk') }}" method="POST" x-data="{ selectedCount: 0 }" id="bulkForm">
                @csrf
                <div class="flex justify-between items-center bg-dark-50 p-3 rounded-xl mb-4 border border-dark-100 transition-opacity" :class="selectedCount > 0 ? 'opacity-100' : 'opacity-0 pointer-events-none'">
                    <span class="font-bold text-primary-600"><span x-text="selectedCount"></span> Slot Terpilih</span>
                    <div class="flex gap-2">
                        <button type="submit" name="action" value="block" class="btn-secondary py-1 px-3 text-xs bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 border-red-200" onclick="return confirm('Blokir slot terpilih?')"><i class="fas fa-ban mr-1"></i>Blokir</button>
                        <button type="submit" name="action" value="unblock" class="btn-secondary py-1 px-3 text-xs bg-emerald-50 text-emerald-600 hover:bg-emerald-100 hover:text-emerald-700 border-emerald-200"><i class="fas fa-check mr-1"></i>Buka Blokir</button>
                        <button type="submit" name="action" value="delete" class="btn-secondary py-1 px-3 text-xs bg-dark-800 text-white hover:bg-dark-900 border-dark-800" onclick="return confirm('Hapus slot terpilih secara permanen?')"><i class="fas fa-trash mr-1"></i>Hapus</button>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($slots as $slot)
                    @php
                        $isBooked = in_array($slot->status, ['booked', 'reserved']);
                        $isCompleted = $slot->status === 'completed';
                        $isBlocked = $slot->status === 'blocked';
                        $isAvailable = $slot->status === 'available';
                        
                        $bgClass = $isBooked ? 'bg-amber-50 border-amber-200' : 
                                  ($isCompleted ? 'bg-emerald-50 border-emerald-200' : 
                                  ($isBlocked ? 'bg-red-50 border-red-200' : 'bg-white border-dark-200 hover:border-primary-300'));
                        $textClass = $isBooked ? 'text-amber-700' : 
                                    ($isCompleted ? 'text-emerald-700' : 
                                    ($isBlocked ? 'text-red-700' : 'text-dark-700'));
                    @endphp
                    <label class="relative block cursor-pointer group {{ !$isAvailable && !$isBlocked ? 'pointer-events-none opacity-80' : '' }}">
                        @if($isAvailable || $isBlocked)
                        <input type="checkbox" name="slot_ids[]" value="{{ $slot->id }}" class="peer sr-only" @change="$event.target.checked ? selectedCount++ : selectedCount--">
                        <div class="absolute inset-0 border-2 border-primary-500 rounded-xl opacity-0 peer-checked:opacity-100 transition-opacity z-10 pointer-events-none"></div>
                        @endif
                        
                        <div class="border-2 rounded-xl p-3 text-center transition-all duration-200 {{ $bgClass }} relative">
                            <p class="font-display font-bold text-lg {{ $textClass }}">{{ substr($slot->jam_mulai, 0, 5) }} - {{ substr($slot->jam_selesai, 0, 5) }}</p>
                            <div class="mt-1 flex items-center justify-center">
                                @if($isAvailable)
                                <span class="text-[10px] font-bold text-dark-400 uppercase tracking-widest">Tersedia</span>
                                @elseif($isBlocked)
                                <span class="text-[10px] font-bold text-red-500 uppercase tracking-widest"><i class="fas fa-ban mr-1"></i>Diblokir</span>
                                @elseif($isCompleted)
                                <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest"><i class="fas fa-check-double mr-1"></i>Selesai</span>
                                @else
                                <span class="text-[10px] font-bold text-amber-500 uppercase tracking-widest"><i class="fas fa-lock mr-1"></i>Booked</span>
                                @endif
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection

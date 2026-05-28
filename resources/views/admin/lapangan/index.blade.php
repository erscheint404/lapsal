@extends('layouts.admin')
@section('title', 'Manajemen Lapangan')
@section('page_title', 'Daftar Lapangan')

@section('content')
<div class="bg-white rounded-2xl border border-dark-100 shadow-sm overflow-hidden">
    {{-- Header & Toolbar --}}
    <div class="p-6 border-b border-dark-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('admin.lapangan.index') }}" method="GET" class="flex gap-2 w-full md:w-auto">
            <div class="relative w-full md:w-64">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-dark-400"><i class="fas fa-search"></i></span>
                <input type="text" name="search" value="{{ request('search') }}" class="form-input py-2 pl-10" placeholder="Cari nama lapangan...">
            </div>
            <select name="status" class="form-input py-2" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            @if(request()->anyFilled(['search', 'status']))
            <a href="{{ route('admin.lapangan.index') }}" class="btn-secondary py-2 px-3"><i class="fas fa-times"></i></a>
            @endif
        </form>
        
        <a href="{{ route('admin.lapangan.create') }}" class="btn-primary py-2">
            <i class="fas fa-plus mr-2"></i> Tambah Lapangan
        </a>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-dark-50 border-b border-dark-100">
                    <th class="py-4 px-6 text-xs font-bold text-dark-500 uppercase tracking-wider">Info Lapangan</th>
                    <th class="py-4 px-6 text-xs font-bold text-dark-500 uppercase tracking-wider">Tipe</th>
                    <th class="py-4 px-6 text-xs font-bold text-dark-500 uppercase tracking-wider">Harga/Jam</th>
                    <th class="py-4 px-6 text-xs font-bold text-dark-500 uppercase tracking-wider">Status</th>
                    <th class="py-4 px-6 text-xs font-bold text-dark-500 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-100">
                @forelse($lapangan as $l)
                <tr class="hover:bg-dark-50/50 transition-colors">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-xl bg-dark-100 overflow-hidden flex-none">
                                @if($l->foto_utama)
                                <img src="{{ Storage::url($l->foto_utama) }}" class="w-full h-full object-cover">
                                @else
                                <div class="w-full h-full flex items-center justify-center text-dark-300"><i class="fas fa-image"></i></div>
                                @endif
                            </div>
                            <div>
                                <p class="font-bold text-dark-900 mb-1">{{ $l->nama }}</p>
                                <div class="flex items-center gap-1 text-xs font-bold text-amber-500">
                                    <i class="fas fa-star"></i> {{ $l->rata_rating }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <span class="badge badge-success">{{ ucfirst(str_replace('_', ' ', $l->tipe)) }}</span>
                    </td>
                    <td class="py-4 px-6">
                        <span class="font-bold text-primary-600">Rp {{ number_format($l->harga_per_jam, 0, ',', '.') }}</span>
                    </td>
                    <td class="py-4 px-6">
                        <span class="badge {{ $l->status === 'aktif' ? 'badge-success' : 'badge-error' }}">
                            {{ ucfirst($l->status) }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.lapangan.show', $l->id) }}" class="btn-icon text-dark-500 hover:text-primary-600 bg-dark-50 hover:bg-primary-50" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.lapangan.edit', $l->id) }}" class="btn-icon text-dark-500 hover:text-amber-600 bg-dark-50 hover:bg-amber-50" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.lapangan.destroy', $l->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus lapangan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon text-dark-500 hover:text-red-600 bg-dark-50 hover:bg-red-50" title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-12 text-center text-dark-500">
                        Belum ada data lapangan.
                        <br>
                        <a href="{{ route('admin.lapangan.create') }}" class="text-primary-600 font-medium hover:underline mt-2 inline-block">Tambah Lapangan Sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-6 border-t border-dark-100">
        {{ $lapangan->links() }}
    </div>
</div>
@endsection

@extends('layouts.admin')
@section('title', 'Data Lapangan')
@section('page_title', 'Data Lapangan')
@section('page_description', 'Kelola informasi, fasilitas, dan harga lapangan futsal.')

@section('page_actions')
<a href="{{ route('admin.lapangan.create') }}" class="btn-primary">
    <i class="fas fa-plus mr-2"></i> Tambah Lapangan
</a>
@endsection

@section('content')
<div class="card-premium overflow-hidden">
    <div class="p-6 border-b border-dark-100/60 flex flex-col sm:flex-row sm:items-center justify-between gap-4" style="background: rgba(0,0,0,0.01);">
        <div class="relative w-full sm:w-64">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-dark-400"><i class="fas fa-search"></i></span>
            <input type="text" class="w-full py-2.5 pl-11 pr-4 text-sm bg-white border border-dark-100/80 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm" placeholder="Cari lapangan...">
        </div>
        <div class="flex gap-2">
            <select class="py-2.5 px-4 text-sm bg-white border border-dark-100/80 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 shadow-sm outline-none">
                <option value="">Semua Tipe</option>
                <option value="vinyl">Vinyl</option>
                <option value="rumput_sintetis">Rumput Sintetis</option>
            </select>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="table-modern w-full">
            <thead>
                <tr>
                    <th class="py-4 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider w-16">No</th>
                    <th class="py-4 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider">Lapangan</th>
                    <th class="py-4 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider">Tipe</th>
                    <th class="py-4 px-6 text-left text-xs font-bold text-dark-400 uppercase tracking-wider">Harga/Jam</th>
                    <th class="py-4 px-6 text-center text-xs font-bold text-dark-400 uppercase tracking-wider">Status</th>
                    <th class="py-4 px-6 text-right text-xs font-bold text-dark-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-100/40">
                @forelse($lapangan as $key => $l)
                <tr class="hover:bg-dark-50/50 transition-colors group">
                    <td class="py-4 px-6 text-sm font-medium text-dark-500">{{ $lapangan->firstItem() + $key }}</td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-dark-100 shrink-0 border border-dark-100">
                                @if($l->foto_utama)
                                <img src="{{ Storage::url($l->foto_utama) }}" class="w-full h-full object-cover">
                                @else
                                <div class="w-full h-full flex items-center justify-center text-dark-300"><i class="fas fa-image"></i></div>
                                @endif
                            </div>
                            <div>
                                <div class="font-bold text-dark-900 mb-1">{{ $l->nama }}</div>
                                <div class="text-xs text-dark-500 flex items-center gap-2">
                                    <span class="flex items-center gap-1" style="color: #6e8f00;"><i class="fas fa-star" style="color: #a3cc00;"></i> {{ $l->rata_rating }}</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $l->tipe)) }}</span>
                    </td>
                    <td class="py-4 px-6">
                        <div class="font-bold text-dark-900">Rp {{ number_format($l->harga_per_jam, 0, ',', '.') }}</div>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <span class="badge" style="background: rgba(204,255,0,0.15); color: #526b00; border: 1px solid rgba(204,255,0,0.3);">Aktif</span>
                    </td>
                    <td class="py-4 px-6 text-right">
                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('admin.lapangan.edit', $l->id) }}" class="w-8 h-8 rounded-lg flex items-center justify-center bg-dark-50 text-dark-500 hover:bg-primary-50 hover:text-primary-600 transition-colors tooltip" data-tip="Edit">
                                <i class="fas fa-pen text-sm"></i>
                            </a>
                            <form action="{{ route('admin.lapangan.destroy', $l->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus lapangan ini?');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center bg-dark-50 text-dark-500 hover:bg-red-50 hover:text-red-600 transition-colors tooltip" data-tip="Hapus">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                        <div class="md:hidden flex items-center justify-end gap-2 group-hover:hidden">
                            <i class="fas fa-ellipsis-h text-dark-300"></i>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background: rgba(0,0,0,0.03); color: #627d9e;">
                            <i class="fas fa-layer-group text-2xl"></i>
                        </div>
                        <p class="text-dark-900 font-bold mb-1">Belum ada data lapangan</p>
                        <p class="text-sm text-dark-500 mb-4">Mulai tambahkan lapangan futsal pertama Anda.</p>
                        <a href="{{ route('admin.lapangan.create') }}" class="btn-primary btn-sm">Tambah Lapangan</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($lapangan->hasPages())
    <div class="p-5 border-t border-dark-100/60" style="background: rgba(0,0,0,0.01);">
        {{ $lapangan->links() }}
    </div>
    @endif
</div>
@endsection
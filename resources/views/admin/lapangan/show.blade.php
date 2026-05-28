@extends('layouts.admin')
@section('title', 'Detail Lapangan')
@section('page_title', 'Detail Lapangan: ' . $lapangan->nama)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl border border-dark-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-dark-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.lapangan.index') }}" class="btn-icon text-dark-500 bg-dark-50 hover:bg-dark-100"><i class="fas fa-arrow-left"></i></a>
                    <h2 class="font-bold text-lg text-dark-900">Informasi Lapangan</h2>
                </div>
                <a href="{{ route('admin.lapangan.edit', $lapangan->id) }}" class="btn-primary py-2 px-4"><i class="fas fa-edit mr-2"></i>Edit</a>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-2 gap-y-6 mb-8">
                    <div>
                        <p class="text-xs font-bold text-dark-400 uppercase tracking-wider mb-1">Status</p>
                        <span class="badge {{ $lapangan->status === 'aktif' ? 'badge-success' : 'badge-error' }}">{{ ucfirst($lapangan->status) }}</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-dark-400 uppercase tracking-wider mb-1">Tipe Lapangan</p>
                        <span class="badge badge-success">{{ ucfirst(str_replace('_', ' ', $lapangan->tipe)) }}</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-dark-400 uppercase tracking-wider mb-1">Harga per Jam</p>
                        <p class="font-bold text-xl text-primary-600">Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-dark-400 uppercase tracking-wider mb-1">Rating</p>
                        <div class="flex items-center text-amber-500 font-bold">
                            <i class="fas fa-star mr-1"></i> {{ $lapangan->rata_rating }}
                            <span class="text-dark-500 text-xs ml-1 font-normal">({{ $lapangan->ratings->count() }} ulasan)</span>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <p class="text-xs font-bold text-dark-400 uppercase tracking-wider mb-2">Deskripsi</p>
                    <p class="text-dark-700 leading-relaxed bg-dark-50 p-4 rounded-xl border border-dark-100">{{ $lapangan->deskripsi ?: 'Tidak ada deskripsi' }}</p>
                </div>

                @if($lapangan->fasilitas && count($lapangan->fasilitas) > 0)
                <div>
                    <p class="text-xs font-bold text-dark-400 uppercase tracking-wider mb-2">Fasilitas</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($lapangan->fasilitas as $fasilitas)
                        <span class="inline-flex items-center px-3 py-1 bg-dark-50 border border-dark-200 text-dark-700 rounded-lg text-sm">
                            <i class="fas fa-check text-primary-500 mr-2 text-xs"></i>{{ $fasilitas }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Booking Terakhir --}}
        <div class="bg-white rounded-2xl border border-dark-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-dark-100">
                <h3 class="font-bold text-lg text-dark-900">Booking Terakhir</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-dark-50 border-b border-dark-100">
                        <tr>
                            <th class="py-3 px-6 text-xs font-bold text-dark-500 uppercase">Member</th>
                            <th class="py-3 px-6 text-xs font-bold text-dark-500 uppercase">Tanggal</th>
                            <th class="py-3 px-6 text-xs font-bold text-dark-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dark-100">
                        @forelse($lapangan->bookings as $booking)
                        <tr class="hover:bg-dark-50">
                            <td class="py-3 px-6 text-sm font-medium text-dark-900">{{ $booking->user->name }}</td>
                            <td class="py-3 px-6 text-sm text-dark-600">{{ $booking->tanggal->format('d M Y') }}</td>
                            <td class="py-3 px-6"><span class="badge {{ $booking->status_color }} text-xs">{{ $booking->status_label }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-6 text-center text-dark-500 text-sm">Belum ada booking</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 bg-dark-50 border-t border-dark-100 text-center">
                <a href="{{ route('admin.booking.index') }}?lapangan_id={{ $lapangan->id }}" class="text-primary-600 text-sm font-medium hover:underline">Lihat Semua Booking</a>
            </div>
        </div>
    </div>

    {{-- Galeri Foto --}}
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-2xl border border-dark-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-dark-100">
                <h3 class="font-bold text-lg text-dark-900">Galeri Foto</h3>
            </div>
            
            <div class="p-4 space-y-4">
                <div>
                    <p class="text-xs font-bold text-dark-400 uppercase tracking-wider mb-2 px-2">Foto Utama</p>
                    <div class="aspect-video rounded-xl overflow-hidden bg-dark-100">
                        @if($lapangan->foto_utama)
                        <img src="{{ Storage::url($lapangan->foto_utama) }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-dark-300"><i class="fas fa-image text-3xl"></i></div>
                        @endif
                    </div>
                </div>

                @if($lapangan->fotoLapangan->count() > 0)
                <div>
                    <p class="text-xs font-bold text-dark-400 uppercase tracking-wider mb-2 px-2 mt-6">Foto Tambahan</p>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($lapangan->fotoLapangan as $foto)
                        <div class="aspect-square rounded-xl overflow-hidden bg-dark-100">
                            <img src="{{ Storage::url($foto->path) }}" class="w-full h-full object-cover">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

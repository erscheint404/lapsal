@extends('layouts.app')
@section('title', 'Daftar Lapangan')
@section('content')
<div class="bg-dark-50 py-12">
    <div class="container-custom">
        {{-- Header & Filter --}}
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-display font-bold text-dark-900 mb-6">Pilih Lapangan Futsal</h1>
            
            <form action="{{ route('lapangan.index') }}" method="GET" class="card p-4 flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-400"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-input pl-11" placeholder="Cari nama lapangan...">
                </div>
                
                <div class="w-full md:w-48">
                    <select name="tipe" class="form-input">
                        <option value="">Semua Tipe</option>
                        <option value="vinyl" {{ request('tipe') == 'vinyl' ? 'selected' : '' }}>Vinyl</option>
                        <option value="rumput_sintetis" {{ request('tipe') == 'rumput_sintetis' ? 'selected' : '' }}>Rumput Sintetis</option>
                        <option value="semen" {{ request('tipe') == 'semen' ? 'selected' : '' }}>Semen</option>
                        <option value="parquette" {{ request('tipe') == 'parquette' ? 'selected' : '' }}>Parquette (Kayu)</option>
                    </select>
                </div>
                
                <div class="w-full md:w-48">
                    <select name="sort" class="form-input">
                        <option value="">Terbaru</option>
                        <option value="harga_asc" {{ request('sort') == 'harga_asc' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="harga_desc" {{ request('sort') == 'harga_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="nama" {{ request('sort') == 'nama' ? 'selected' : '' }}>Nama A-Z</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-primary w-full md:w-auto"><i class="fas fa-filter mr-2"></i>Filter</button>
                @if(request()->anyFilled(['search', 'tipe', 'sort']))
                <a href="{{ route('lapangan.index') }}" class="btn-secondary w-full md:w-auto px-4"><i class="fas fa-times"></i></a>
                @endif
            </form>
        </div>

        {{-- Grid Lapangan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($lapangan as $l)
            <a href="{{ route('lapangan.show', $l->id) }}" class="group block h-full">
                <div class="card h-full overflow-hidden flex flex-col transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="relative h-56 overflow-hidden">
                        @if($l->foto_utama)
                        <img src="{{ Storage::url($l->foto_utama) }}" alt="{{ $l->nama }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @else
                        <div class="w-full h-full bg-dark-100 flex items-center justify-center">
                            <i class="fas fa-image text-4xl text-dark-300"></i>
                        </div>
                        @endif
                        <div class="absolute top-4 left-4">
                            <span class="badge badge-success shadow-lg backdrop-blur-md bg-white/90">
                                {{ ucfirst(str_replace('_', ' ', $l->tipe)) }}
                            </span>
                        </div>
                    </div>
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex justify-between items-start mb-2 gap-2">
                            <h3 class="font-display font-bold text-lg text-dark-900 line-clamp-2">{{ $l->nama }}</h3>
                            <div class="flex items-center gap-1 bg-amber-50 text-amber-700 px-2 py-1 rounded-lg text-xs font-bold">
                                <i class="fas fa-star text-amber-500"></i> {{ $l->rata_rating }}
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap gap-2 mb-4">
                            @if(is_array($l->fasilitas))
                                @foreach(array_slice($l->fasilitas, 0, 3) as $fasilitas)
                                <span class="text-xs text-dark-500 bg-dark-50 px-2 py-1 rounded-md border border-dark-100">{{ $fasilitas }}</span>
                                @endforeach
                                @if(count($l->fasilitas) > 3)
                                <span class="text-xs text-dark-500 bg-dark-50 px-2 py-1 rounded-md border border-dark-100">+{{ count($l->fasilitas) - 3 }}</span>
                                @endif
                            @endif
                        </div>
                        
                        <div class="mt-auto pt-4 border-t border-dark-100 flex items-center justify-between">
                            <div>
                                <p class="text-xs text-dark-400 font-medium">Harga / Jam</p>
                                <p class="font-bold text-primary-600 text-lg">Rp {{ number_format($l->harga_per_jam, 0, ',', '.') }}</p>
                            </div>
                            <div class="btn-primary py-2 px-4 rounded-xl shadow-none group-hover:shadow-lg group-hover:shadow-primary-500/30 transition-shadow">
                                Pesan
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full py-20 text-center">
                <div class="w-24 h-24 bg-dark-100 rounded-full flex items-center justify-center mx-auto mb-4 text-dark-400 text-4xl">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="text-xl font-bold text-dark-900 mb-2">Tidak ditemukan</h3>
                <p class="text-dark-500">Coba ubah kata kunci atau filter pencarian.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $lapangan->links() }}
        </div>
    </div>
</div>
@endsection
